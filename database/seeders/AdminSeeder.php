<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Отримуємо роль admin (має бути створена RolesSeeder)
        $role = Role::where('name', 'admin')->first();

        // Створюємо адміністратора
        $user = User::updateOrCreate([
            'email' => config('app.admin.email', 'admin@example.com')
        ], [
            'name' => config('app.admin.name', 'Administrator'),
            'password' => bcrypt(config('app.admin.password', 'password')),
        ]);

        // Призначаємо роль admin
        if ($role && !$user->hasRole('admin')) {
            $user->assignRole($role);
        }
    }
}
