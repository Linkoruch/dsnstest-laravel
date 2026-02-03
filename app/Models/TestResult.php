<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TestResult extends Model
{
    protected $fillable = [
        'user_id',
        'test_id',
        'result_file_path',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Отримати результат з JSON файлу
     */
    public function getResult(): ?array
    {
        if (!$this->result_file_path || !Storage::exists($this->result_file_path)) {
            return null;
        }

        $content = Storage::get($this->result_file_path);
        return json_decode($content, true);
    }

    /**
     * Alias для getResult() (для сумісності)
     */
    public function getResults(): ?array
    {
        return $this->getResult();
    }

    /**
     * Розрахувати відсоток правильних відповідей
     */
    public function getScorePercentage(): float
    {
        $result = $this->getResult();

        if (!$result || !isset($result['total_questions']) || $result['total_questions'] == 0) {
            return 0;
        }

        $correctAnswers = $result['correct_answers'] ?? 0;
        $totalQuestions = $result['total_questions'];

        return round(($correctAnswers / $totalQuestions) * 100, 2);
    }
}
