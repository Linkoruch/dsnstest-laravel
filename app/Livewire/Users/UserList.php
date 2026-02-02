<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $deleteId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
    }

    public function delete()
    {
        if (!$this->deleteId) {
            return;
        }

        $user = User::findOrFail($this->deleteId);

        // Перевіряємо, щоб не видалити самого себе
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Ви не можете видалити самого себе!');
            $this->deleteId = null;
            return;
        }

        $user->delete();

        $this->deleteId = null;
        session()->flash('success', 'Користувача успішно видалено!');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->role($this->roleFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.users.user-list', [
            'users' => $users
        ])->layout('layouts.app');
    }
}
