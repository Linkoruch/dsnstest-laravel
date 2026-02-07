<?php

namespace App\Livewire\Tests;

use App\Models\Test;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class CreateTest extends Component
{
    public $name = '';
    public $description = '';
    public $questions = [];
    public $successMessage = '';
    public $selectedRiskLevels = []; // Масив обраних рівнів ризику
    public $attempts_limit = null; // Кількість спроб (null = необмежено)

    public function mount()
    {

        // Додаємо одне порожнє питання для початку
        $this->addQuestion(false); // false = не прокручувати при mount
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'attempts_limit' => 'nullable|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_answer' => 'required|in:A,B,C,D',
        ];
    }

    protected $messages = [
        'name.required' => 'Назва тесту є обов\'язковою',
        'name.max' => 'Назва тесту не може перевищувати 255 символів',
        'attempts_limit.integer' => 'Кількість спроб має бути числом',
        'attempts_limit.min' => 'Кількість спроб має бути не менше 1',
        'questions.required' => 'Додайте хоча б одне питання',
        'questions.min' => 'Додайте хоча б одне питання',
        'questions.*.question_text.required' => 'Текст питання є обов\'язковим',
        'questions.*.option_a.required' => 'Варіант A є обов\'язковим',
        'questions.*.option_b.required' => 'Варіант B є обов\'язковим',
        'questions.*.option_c.required' => 'Варіант C є обов\'язковим',
        'questions.*.option_d.required' => 'Варіант D є обов\'язковим',
        'questions.*.correct_answer.required' => 'Оберіть правильну відповідь',
    ];

    public function addQuestion($triggerScroll = true)
    {
        $this->questions[] = [
            'question_text' => '',
            'option_a' => '',
            'option_b' => '',
            'option_c' => '',
            'option_d' => '',
            'correct_answer' => 'A',
        ];

        // Викликаємо подію для прокручування вниз ТІЛЬКИ якщо це ручне додавання
        if ($triggerScroll) {
            $this->dispatch('questionAdded');
        }
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions); // Переіндексуємо масив
    }

    public function save()
    {
        $this->validate();

        // Формуємо JSON структуру
        $jsonData = [
            'questions' => []
        ];

        foreach ($this->questions as $index => $question) {
            $jsonData['questions'][] = [
                'question_id' => $index + 1,
                'question_text' => $question['question_text'],
                'options' => [
                    'A' => $question['option_a'],
                    'B' => $question['option_b'],
                    'C' => $question['option_c'],
                    'D' => $question['option_d'],
                ],
                'correct_answer' => $question['correct_answer'],
            ];
        }

        // Створюємо JSON файл
        $fileName = 'tests/questions_' . time() . '_' . uniqid() . '.json';
        Storage::put($fileName, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Створюємо тест
        Test::create([
            'name' => $this->name,
            'description' => $this->description,
            'questions_file_path' => $fileName,
            'risk_levels' => empty($this->selectedRiskLevels) ? null : $this->selectedRiskLevels,
            'attempts_limit' => $this->attempts_limit,
        ]);

        // Показуємо повідомлення про успіх (залишаємось на сторінці)
        $this->successMessage = 'Тест успішно створено!';

        // Скидаємо форму для створення нового тесту
        $this->reset(['name', 'description', 'questions', 'selectedRiskLevels', 'attempts_limit']);
        $this->addQuestion(false); // Додаємо одне порожнє питання

        // Dispatch події для можливого використання в JavaScript
        $this->dispatch('test-created');
    }

    public function render()
    {
        return view('livewire.tests.create-test')->layout('layouts.app');
    }
}


