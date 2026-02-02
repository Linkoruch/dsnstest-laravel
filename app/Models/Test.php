<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'questions_file_path',
        'assigned_users',
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
            'assigned_users' => 'array',
        ];
    }

    /**
     * Отримати питання з JSON файлу
     */
    public function getQuestions(): ?array
    {
        if (!$this->questions_file_path || !file_exists(storage_path('app/' . $this->questions_file_path))) {
            return null;
        }

        $content = file_get_contents(storage_path('app/' . $this->questions_file_path));
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
        // Якщо assigned_usersNull або порожній - тест доступний всім
        if (empty($this->assigned_users)) {
            return true;
        }

        // Перевіряємо чи є 'all' в масиві (доступний всім)
        if (in_array('all', $this->assigned_users)) {
            return true;
        }

        // Перевіряємо чи є ID користувача в списку
        return in_array($userId, $this->assigned_users);
    }

    /**
     * Отримати список ID користувачів, яким доступний тест
     */
    public function getAssignedUserIds(): array
    {
        if (empty($this->assigned_users)) {
            return [];
        }

        return $this->assigned_users;
    }
}
