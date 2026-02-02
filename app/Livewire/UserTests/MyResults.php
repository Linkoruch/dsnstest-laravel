<?php

namespace App\Livewire\UserTests;

use App\Models\TestResult;
use Livewire\Component;
use Livewire\WithPagination;

class MyResults extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'completed_at';
    public $sortDirection = 'desc';

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

    public function render()
    {
        $results = TestResult::with('test')
            ->where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->whereHas('test', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.user-tests.my-results', [
            'results' => $results
        ])->layout('layouts.app');
    }
}
