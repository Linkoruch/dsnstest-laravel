<?php

namespace App\Livewire\TestResults;

use App\Models\Test;
use App\Models\User;
use App\Models\UserTestAttempt;
use Livewire\Component;
use Livewire\WithPagination;

class ManageAttempts extends Component
{
    use WithPagination;

    public Test $test;
    public $selectedUserId = null;
    public $bonusAttempts = 1;
    public $showModal = false;
    public $actionType = ''; // 'reset', 'add', або 'reset_all'

    public function openModal($userId, $action)
    {
        $this->selectedUserId = $userId;
        $this->actionType = $action;
        $this->showModal = true;
        $this->bonusAttempts = 1;
    }

    public function openResetAllModal()
    {
        $this->actionType = 'reset_all';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedUserId = null;
        $this->actionType = '';
    }

    public function resetAttempts()
    {
        $attempt = UserTestAttempt::firstOrCreate(
            [
                'user_id' => $this->selectedUserId,
                'test_id' => $this->test->id,
            ],
            [
                'attempts_used' => 0,
                'bonus_attempts' => 0,
            ]
        );

        $attempt->resetAttempts();

        session()->flash('success', 'Спроби користувача успішно обнулено!');
        $this->closeModal();
    }

    public function addBonusAttempts()
    {
        $this->validate([
            'bonusAttempts' => 'required|integer|min:1|max:100',
        ], [
            'bonusAttempts.required' => 'Вкажіть кількість спроб',
            'bonusAttempts.integer' => 'Кількість має бути числом',
            'bonusAttempts.min' => 'Мінімум 1 спроба',
            'bonusAttempts.max' => 'Максимум 100 спроб',
        ]);

        $attempt = UserTestAttempt::firstOrCreate(
            [
                'user_id' => $this->selectedUserId,
                'test_id' => $this->test->id,
            ],
            [
                'attempts_used' => 0,
                'bonus_attempts' => 0,
            ]
        );

        $attempt->addBonusAttempts($this->bonusAttempts);

        session()->flash('success', "Додано {$this->bonusAttempts} додаткових спроб!");
        $this->closeModal();
    }

    public function resetAllAttempts()
    {
        // Отримуємо всіх користувачів з роллю user
        $users = User::role('user')->get();
        $resetCount = 0;

        foreach ($users as $user) {
            $attempt = UserTestAttempt::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'test_id' => $this->test->id,
                ],
                [
                    'attempts_used' => 0,
                    'bonus_attempts' => 0,
                ]
            );

            $attempt->resetAttempts();
            $resetCount++;
        }

        session()->flash('success', "Спроби обнулено для {$resetCount} користувачів!");
        $this->closeModal();
    }

    public function render()
    {
        // Отримуємо користувачів з роллю user
        $users = User::role('user')
            ->with(['testAttempts' => function ($query) {
                $query->where('test_id', $this->test->id);
            }])
            ->paginate(10);

        return view('livewire.test-results.manage-attempts', [
            'users' => $users
        ])->layout('layouts.app');
    }
}

