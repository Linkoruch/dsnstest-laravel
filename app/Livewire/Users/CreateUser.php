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
    public $successMessage = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
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
    ];

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Призначаємо роль
        $user->assignRole($this->role);

        $this->successMessage = 'Користувача успішно створено!';

        // Скидаємо форму
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role']);
        $this->role = 'user'; // Встановлюємо значення за замовчуванням
    }

    public function render()
    {
        return view('livewire.users.create-user')->layout('layouts.app');
    }
}
