<?php

namespace App\Livewire\TestResults;

use App\Models\TestResult;
use Livewire\Component;
use Livewire\WithPagination;

class TestResultList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'completed_at';
    public $sortDirection = 'desc';
    public $deleteId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
    }

    public function delete()
    {
        if (!$this->deleteId) {
            return;
        }

        $testResult = TestResult::findOrFail($this->deleteId);

        // Видаляємо JSON файл результатів
        if ($testResult->result_file_path && file_exists(storage_path('app/' . $testResult->result_file_path))) {
            unlink(storage_path('app/' . $testResult->result_file_path));
        }

        $testResult->delete();

        $this->deleteId = null;
        session()->flash('success', 'Результат тесту успішно видалено!');
    }

    public function render()
    {
        $results = TestResult::with(['user', 'test'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('test', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.test-results.test-result-list', [
            'results' => $results
        ])->layout('layouts.app');
    }
}
