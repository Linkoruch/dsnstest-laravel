<?php

namespace App\Livewire\UserTests;

use App\Models\Test;
use App\Models\TestResult;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TakeTest extends Component
{
    public Test $test;
    public $questions = [];
    public $answers = [];
    public $currentQuestionIndex = 0;
    public $isCompleted = false;
    public $startTime; // Час початку тесту
    public $timeRemaining; // Залишок часу в секундах

    public function mount(Test $test)
    {
        $this->test = $test;

        // Перевіряємо чи може користувач пройти тест
        if (!$test->canUserTakeTest(auth()->id())) {
            session()->flash('error', 'У вас немає доступу до цього тесту або вичерпано кількість спроб.');
            return redirect()->route('user.tests.available');
        }

        // Отримуємо випадкові питання згідно з налаштуваннями
        $questionsData = $test->getRandomQuestions();

        if ($questionsData && isset($questionsData['questions'])) {
            $this->questions = $questionsData['questions'];

            // Ініціалізуємо масив відповідей
            foreach ($this->questions as $question) {
                $this->answers[$question['question_id']] = '';
            }
        }

        // Ініціалізуємо таймер, якщо є обмеження часу
        if ($test->duration_minutes) {
            $this->startTime = now();
            $this->timeRemaining = $test->duration_minutes * 60; // Конвертуємо хвилини в секунди
        }
    }

    public function selectAnswer($questionId, $answer)
    {
        $this->answers[$questionId] = $answer;
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function goToQuestion($index)
    {
        $this->currentQuestionIndex = $index;
    }

    public function submitTest()
    {
        // Перевіряємо, чи відповіли на всі питання
        $unanswered = [];
        foreach ($this->questions as $question) {
            if (empty($this->answers[$question['question_id']])) {
                $unanswered[] = $question['question_id'];
            }
        }

        if (!empty($unanswered)) {
            session()->flash('error', 'Будь ласка, дайте відповідь на всі питання!');
            return;
        }

        $this->completeTest();
    }

    public function autoSubmitTest()
    {
        // Автоматичне завершення при закінченні часу
        // Не вимагає відповіді на всі питання
        $this->completeTest();
    }

    private function completeTest()
    {
        // Підраховуємо правильні відповіді
        $correctAnswers = 0;
        $detailedAnswers = [];

        foreach ($this->questions as $question) {
            $givenAnswer = $this->answers[$question['question_id']] ?? '';

            // Якщо відповідь порожня, вважаємо її неправильною
            $isCorrect = !empty($givenAnswer) && $givenAnswer === $question['correct_answer'];

            if ($isCorrect) {
                $correctAnswers++;
            }

            $detailedAnswers[] = [
                'question_id' => $question['question_id'],
                'given_answer' => $givenAnswer ?: null,
                'is_correct' => $isCorrect
            ];
        }

        // Створюємо JSON файл з результатами
        $resultData = [
            'correct_answers' => $correctAnswers,
            'total_questions' => count($this->questions),
            'answers' => $detailedAnswers
        ];

        $fileName = 'test-results/result_' . auth()->id() . '_' . $this->test->id . '_' . time() . '_' . uniqid() . '.json';
        Storage::put($fileName, json_encode($resultData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Зберігаємо результат у базу даних
        $testResult = TestResult::create([
            'user_id' => auth()->id(),
            'test_id' => $this->test->id,
            'result_file_path' => $fileName,
            'completed_at' => now()
        ]);

        // Використовуємо одну спробу
        $attempt = $this->test->getUserAttempt(auth()->id());
        $attempt->useAttempt();

        // Редиректимо на сторінку з результатами
        return redirect()->route('user.result.view', $testResult);
    }

    public function render()
    {
        return view('livewire.user-tests.take-test')->layout('layouts.app');
    }
}
