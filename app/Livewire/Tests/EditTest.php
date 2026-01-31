<?php

namespace App\Livewire\Tests;

use App\Models\Test;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditTest extends Component
{
    use WithFileUploads;

    public Test $test;
    public $name;
    public $description;
    public $questionsFile;

    public function mount(Test $test)
    {
        $this->test = $test;
        $this->name = $test->name;
        $this->description = $test->description;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questionsFile' => 'nullable|file|mimes:json|max:2048',
        ];
    }

    protected $messages = [
        'name.required' => 'Назва тесту є обов\'язковою',
        'name.max' => 'Назва тесту не може перевищувати 255 символів',
        'questionsFile.mimes' => 'Файл повинен бути у форматі JSON',
        'questionsFile.max' => 'Файл не може перевищувати 2MB',
    ];

    public function update()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
        ];

        // Якщо завантажено новий файл
        if ($this->questionsFile) {
            // Перевірка формату JSON
            $content = file_get_contents($this->questionsFile->getRealPath());
            $jsonData = json_decode($content, true);

            if (!$jsonData || !isset($jsonData['questions']) || !is_array($jsonData['questions'])) {
                $this->addError('questionsFile', 'Невірний формат JSON файлу. Очікується структура з полем "questions"');
                return;
            }

            // Видаляємо старий файл
            if ($this->test->questions_file_path && file_exists(storage_path('app/' . $this->test->questions_file_path))) {
                unlink(storage_path('app/' . $this->test->questions_file_path));
            }

            // Зберігаємо новий файл
            $fileName = 'tests/questions_' . time() . '_' . uniqid() . '.json';
            $this->questionsFile->storeAs('', $fileName);

            $data['questions_file_path'] = $fileName;
        }

        $this->test->update($data);

        session()->flash('message', 'Тест успішно оновлено!');

        return redirect()->route('tests.index');
    }

    public function render()
    {
        return view('livewire.tests.edit-test')->layout('layouts.app');;
    }
}
