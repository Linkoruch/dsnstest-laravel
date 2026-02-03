<?php

namespace App\Livewire\TestResults;

use App\Models\TestResult;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ViewTestResult extends Component
{
    public TestResult $testResult;
    public $results;
    public $questions;
    public $showDeleteModal = false;

    protected $listeners = ['confirmDelete' => 'showDeleteConfirmation'];

    public function mount(TestResult $testResult)
    {
        $this->testResult = $testResult->load(['user', 'test']);
        $this->results = $testResult->getResults();
        $this->questions = $testResult->test->getQuestions();

        // Якщо дані не завантажилися, встановлюємо порожні масиви
        if (!$this->results) {
            $this->results = ['correct_answers' => 0, 'total_questions' => 0, 'answers' => []];
        }

        if (!$this->questions) {
            $this->questions = ['questions' => []];
        }
    }

    public function showDeleteConfirmation($id)
    {
        if ($id == $this->testResult->id) {
            $this->showDeleteModal = true;
        }
    }

    public function deleteResult()
    {
        // Видаляємо JSON файл результатів
        if ($this->testResult->result_file_path && Storage::exists($this->testResult->result_file_path)) {
            Storage::delete($this->testResult->result_file_path);
        }

        $this->testResult->delete();

        session()->flash('success', 'Результат тесту успішно видалено!');
        return redirect()->route('results.index');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.test-results.view-test-result')->layout('layouts.app');
    }
}
