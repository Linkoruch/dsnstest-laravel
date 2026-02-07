<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTestAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'test_id',
        'attempts_used',
        'bonus_attempts',
    ];

    protected $casts = [
        'attempts_used' => 'integer',
        'bonus_attempts' => 'integer',
    ];

    /**
     * Користувач
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Тест
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Отримати загальну кількість доступних спроб
     */
    public function getTotalAvailableAttempts(): int
    {
        // Перевіряємо чи завантажено зв'язок з тестом
        if (!$this->test) {
            $this->load('test');
        }

        $testLimit = $this->test->attempts_limit;

        // Якщо ліміт не встановлено - необмежено
        if ($testLimit === null) {
            return PHP_INT_MAX;
        }

        return $testLimit + $this->bonus_attempts;
    }

    /**
     * Отримати залишок спроб
     */
    public function getRemainingAttempts(): int
    {
        $total = $this->getTotalAvailableAttempts();

        if ($total === PHP_INT_MAX) {
            return PHP_INT_MAX;
        }

        return max(0, $total - $this->attempts_used);
    }

    /**
     * Чи є доступні спроби
     */
    public function hasAttemptsAvailable(): bool
    {
        return $this->getRemainingAttempts() > 0;
    }

    /**
     * Використати одну спробу
     */
    public function useAttempt(): void
    {
        $this->increment('attempts_used');
    }

    /**
     * Скинути спроби (обнулити)
     */
    public function resetAttempts(): void
    {
        $this->update(['attempts_used' => 0]);
    }

    /**
     * Додати бонусні спроби
     */
    public function addBonusAttempts(int $count): void
    {
        $this->increment('bonus_attempts', $count);
    }
}


