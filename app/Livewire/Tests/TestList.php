<?php

namespace App\Livewire\Tests;

use App\Models\Test;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class TestList extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $testToDelete = null;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($testId)
    {
        $this->testToDelete = $testId;
        $this->showDeleteModal = true;
    }

    public function deleteTest()
    {
        if ($this->testToDelete) {
            $test = Test::find($this->testToDelete);

            if ($test) {
                // Видаляємо файл з питаннями
                if ($test->questions_file_path && Storage::exists($test->questions_file_path)) {
                    Storage::delete($test->questions_file_path);
                }

                $test->delete();

                session()->flash('message', 'Тест успішно видалено!');
            }
        }

        $this->showDeleteModal = false;
        $this->testToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->testToDelete = null;
    }

    public function render()
    {
        $tests = Test::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.tests.test-list', [
            'tests' => $tests
        ])->layout('layouts.app');
    }
}
