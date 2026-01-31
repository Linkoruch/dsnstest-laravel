<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
