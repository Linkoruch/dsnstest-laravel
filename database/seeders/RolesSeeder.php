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

        // Створюємо роль користувача
        Role::firstOrCreate(['name' => 'user'], ['guard_name' => 'web']);
    }
}
