<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Спочатку створюємо ролі
        $this->call(RolesSeeder::class);

        // Потім створюємо адміністратора
        $this->call(AdminSeeder::class);

        // Створюємо тестові дані для тестів
        $this->call(TestSeeder::class);

        // User::factory(10)->create();

        // Створюємо тестового користувача зі студентською роллю
        $testUser = User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
        ]);
        $testUser->assignRole('student');
    }
}
