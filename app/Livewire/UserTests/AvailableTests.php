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
        $user = auth()->user();
        $userId = $user->id;
        $userRiskLevel = $user->risk_level;

        $tests = Test::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->where(function ($query) use ($userRiskLevel) {
                // Тести без рівнів ризику (доступні всім)
                $query->whereNull('risk_levels')
                    // Або тести з рівнем ризику користувача
                    ->orWhere(function ($q) use ($userRiskLevel) {
                        if ($userRiskLevel) {
                            $q->whereNotNull('risk_levels')
                              ->where('risk_levels', 'like', '%"' . $userRiskLevel . '"%');
                        }
                    });
            })
            ->withCount('testResults')
            ->orderBy('created_at', 'desc')
            ->paginate(9);


        return view('livewire.user-tests.available-tests', [
            'tests' => $tests
        ])->layout('layouts.app');
    }
}
