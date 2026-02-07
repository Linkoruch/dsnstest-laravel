<?php

namespace App\Livewire\UserTests;

use App\Models\TestResult;
use Livewire\Component;

class ViewMyResult extends Component
{
    public TestResult $testResult;
    public $results;

    public function mount(TestResult $testResult)
    {
        // Перевірка, що результат належить поточному користувачу
        if ($testResult->user_id !== auth()->id()) {
            abort(403, 'Ви не маєте доступу до цього результату');
        }

        $this->testResult = $testResult->load(['user', 'test']);
        $this->results = $testResult->getResults();

        // Якщо дані не завантажилися, встановлюємо порожні дані
        if (!$this->results) {
            $this->results = ['correct_answers' => 0, 'total_questions' => 0, 'answers' => []];
        }
    }

    public function render()
    {
        return view('livewire.user-tests.view-my-result')->layout('layouts.app');
    }
}
