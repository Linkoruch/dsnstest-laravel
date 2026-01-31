<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Створюємо роль адміністратора
        Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);

        // Створюємо роль студента
        Role::firstOrCreate(['name' => 'student'], ['guard_name' => 'web']);
    }
}
