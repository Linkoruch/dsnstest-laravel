<?php

namespace App\Livewire\TestResults;

use App\Models\TestResult;
use Livewire\Component;

class ViewTestResult extends Component
{
    public TestResult $testResult;
    public $results;
    public $questions;

    public function mount(TestResult $testResult)
    {
        $this->testResult = $testResult->load(['user', 'test']);
        $this->results = $testResult->getResults();
        $this->questions = $testResult->test->getQuestions();
    }

    public function render()
    {
        return view('livewire.test-results.view-test-result')->layout('layouts.app');
    }
}
