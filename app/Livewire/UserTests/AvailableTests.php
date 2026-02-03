<?php

namespace App\Livewire\UserTests;

use App\Models\Test;
use Livewire\Component;
use Livewire\WithPagination;

class AvailableTests extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $userId = auth()->id();

        $tests = Test::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->where(function ($query) use ($userId) {
                // Тести без призначень (доступні всім)
                $query->whereNull('assigned_users')
                    // Або тести з 'all' у призначеннях
                    ->orWhereJsonContains('assigned_users', 'all')
                    // Або тести, де є ID поточного користувача
                    ->orWhereJsonContains('assigned_users', (string)$userId);
            })
            ->withCount('testResults')
            ->orderBy('created_at', 'desc')
            ->paginate(9);


        return view('livewire.user-tests.available-tests', [
            'tests' => $tests
        ])->layout('layouts.app');
    }
}
