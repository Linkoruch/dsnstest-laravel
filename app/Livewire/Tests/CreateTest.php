<?php

namespace App\Livewire\Tests;

use App\Models\Test;
use Livewire\Component;

class CreateTest extends Component
{
    public $name = '';
    public $description = '';
    public $questions = [];

    public function mount()
    {
        // Додаємо одне порожнє питання для початку
        $this->addQuestion();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
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
        'questions.required' => 'Додайте хоча б одне питання',
        'questions.min' => 'Додайте хоча б одне питання',
        'questions.*.question_text.required' => 'Текст питання є обов\'язковим',
        'questions.*.option_a.required' => 'Варіант A є обов\'язковим',
        'questions.*.option_b.required' => 'Варіант B є обов\'язковим',
        'questions.*.option_c.required' => 'Варіант C є обов\'язковим',
        'questions.*.option_d.required' => 'Варіант D є обов\'язковим',
        'questions.*.correct_answer.required' => 'Оберіть правильну відповідь',
    ];

    public function addQuestion()
    {
        $this->questions[] = [
            'question_text' => '',
            'option_a' => '',
            'option_b' => '',
            'option_c' => '',
            'option_d' => '',
            'correct_answer' => 'A',
        ];
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
        $filePath = storage_path('app/' . $fileName);

        // Переконуємось що директорія існує
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        file_put_contents($filePath, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Створюємо тест
        Test::create([
            'name' => $this->name,
            'description' => $this->description,
            'questions_file_path' => $fileName,
        ]);

        session()->flash('message', 'Тест успішно створено!');

        return redirect()->route('tests.index');
    }

    public function render()
    {
        return view('livewire.tests.create-test')->layout('layouts.app');
    }
}


