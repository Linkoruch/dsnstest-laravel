<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Test extends Model
{
    use HasFactory;

    // Константи для рівнів ризику
    const RISK_HIGH = 'high';
    const RISK_LOW = 'low';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'questions_file_path',
        'risk_levels',
        'attempts_limit',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'risk_levels' => 'array',
            'attempts_limit' => 'integer',
        ];
    }

    /**
     * Отримати питання з JSON файлу
     */
    public function getQuestions(): ?array
    {
        if (!$this->questions_file_path || !Storage::exists($this->questions_file_path)) {
            return null;
        }

        $content = Storage::get($this->questions_file_path);
        return json_decode($content, true);
    }

    /**
     * Зв'язок з результатами тестів
     */
    public function testResults(): HasMany
    {
        return $this->hasMany(TestResult::class);
    }

    /**
     * Перевірити, чи має користувач доступ до тесту
     */
    public function isAvailableForUser($userId): bool
    {
        // Отримуємо користувача
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        // Якщо risk_levels порожній або null - тест доступний всім
        if (empty($this->risk_levels)) {
            return true;
        }

        // Перевіряємо чи рівень ризику користувача є в списку доступних рівнів
        return in_array($user->risk_level, $this->risk_levels);
    }

    /**
     * Отримати список рівнів ризику для тесту
     */
    public function getRiskLevels(): array
    {
        if (empty($this->risk_levels)) {
            return [];
        }

        return $this->risk_levels;
    }

    /**
     * Отримати або створити запис про спроби користувача
     */
    public function getUserAttempt($userId): UserTestAttempt
    {
        $attempt = UserTestAttempt::firstOrCreate(
            [
                'user_id' => $userId,
                'test_id' => $this->id,
            ],
            [
                'attempts_used' => 0,
                'bonus_attempts' => 0,
            ]
        );

        // Завантажуємо зв'язок test, якщо він не завантажений
        if (!$attempt->relationLoaded('test')) {
            $attempt->setRelation('test', $this);
        }

        return $attempt;
    }

    /**
     * Перевірити чи може користувач пройти тест (враховуючи спроби)
     */
    public function canUserTakeTest($userId): bool
    {
        // Перевірка доступу за рівнем ризику
        if (!$this->isAvailableForUser($userId)) {
            return false;
        }

        // Якщо ліміт не встановлено - можна проходити
        if ($this->attempts_limit === null) {
            return true;
        }

        // Перевіряємо залишок спроб
        $attempt = $this->getUserAttempt($userId);
        return $attempt->hasAttemptsAvailable();
    }
}
