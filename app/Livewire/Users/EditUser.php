<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditUser extends Component
{
    public User $user;
    public $name;
    public $email;
    public $password = '';
    public $password_confirmation = '';
    public $role;
    public $risk_level;
    public $successMessage = '';

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()->first() ?? 'user';
        $this->risk_level = $user->risk_level ?? 'low';
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable|string|min:8|confirmed',
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
        'password.min' => 'Пароль має містити щонайменше 8 символів',
        'password.confirmed' => 'Паролі не співпадають',
        'role.required' => 'Оберіть роль користувача',
        'risk_level.in' => 'Оберіть коректний рівень ризику',
    ];

    public function update()
    {
        // Очищуємо пароль, якщо він порожній або містить тільки пробіли
        $this->password = trim($this->password);
        if (empty($this->password)) {
            $this->password = '';
            $this->password_confirmation = '';
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'risk_level' => $this->risk_level,
        ];

        // Оновлюємо пароль тільки якщо він вказаний
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);

        // Оновлюємо роль
        $this->user->syncRoles([$this->role]);

        $this->successMessage = 'Дані користувача успішно оновлено!';

        // Очищуємо поля паролів
        $this->reset(['password', 'password_confirmation']);
    }

    public function render()
    {
        return view('livewire.users.edit-user')->layout('layouts.app');
    }
}
