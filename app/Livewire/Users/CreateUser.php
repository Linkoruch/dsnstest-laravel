<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'user';
    public $risk_level = 'low'; // За замовчуванням низький ризик
    public $successMessage = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'risk_level' => 'nullable|in:high,low',
        ];
    }

    protected $messages = [
        'name.required' => 'Ім\'я користувача є обов\'язковим',
        'name.max' => 'Ім\'я не може перевищувати 255 символів',
        'email.required' => 'Email є обов\'язковим',
        'email.email' => 'Email має бути коректним',
        'email.unique' => 'Користувач з таким email вже існує',
        'password.required' => 'Пароль є обов\'язковим',
        'password.min' => 'Пароль має містити щонайменше 8 символів',
        'password.confirmed' => 'Паролі не співпадають',
        'role.required' => 'Оберіть роль користувача',
        'risk_level.in' => 'Оберіть коректний рівень ризику',
    ];

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'risk_level' => $this->risk_level,
        ]);

        // Призначаємо роль
        $user->assignRole($this->role);

        $this->successMessage = 'Користувача успішно створено!';

        // Скидаємо форму
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role', 'risk_level']);
        $this->role = 'user'; // Встановлюємо значення за замовчуванням
        $this->risk_level = 'low'; // Встановлюємо значення за замовчуванням
    }

    public function render()
    {
        return view('livewire.users.create-user')->layout('layouts.app');
    }
}
