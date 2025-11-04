<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $pendingDeleteId = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['userSaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        if (!auth()->user()?->can('edit-users')) {
            $this->dispatch('notify', type: 'danger', message: __('users.unauthorized'));
            return;
        }
        $this->dispatch('editUser', $id);
    }

    public function confirmDelete($id)
    {
        // امنع فتح مودال التأكيد إذا كان المستخدم غير مخوّل بالحذف
        if (!auth()->user()?->can('delete-users')) {
            $this->dispatch('notify', type: 'danger', message: __('users.unauthorized'));
            $this->dispatch('hideConfirmModal');
            return;
        }
        $this->pendingDeleteId = $id;
        $this->dispatch('showConfirmModal');
    }

    public function deleteConfirmed()
    {
        if ($this->pendingDeleteId) {
            $this->delete($this->pendingDeleteId);
            $this->pendingDeleteId = null;
        }
    }

    public function delete($id)
    {
        if (!auth()->user()?->can('delete-users')) {
            $this->dispatch('notify', type: 'danger', message: __('users.unauthorized'));
            $this->dispatch('hideConfirmModal');
            return;
        }
        try {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                $this->dispatch('notify', type: 'success', message: __('users.deleted'));
                $this->dispatch('hideConfirmModal');
                $this->resetPage();
            } else {
                $this->dispatch('notify', type: 'warning', message: __('users.not_found'));
            }
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('users.delete_failed'));
        }
    }

    public function render()
    {
        $query = User::query()
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    // البحث في الترجمة لاسم المستخدم حسب اللغة الحالية
                    $qq->whereTranslationLike('name', "%{$this->search}%")
                       ->orWhere('username', 'like', "%{$this->search}%")
                       ->orWhere('phone', 'like', "%{$this->search}%")
                       ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->orderByDesc('id');

        $users = $query->paginate($this->perPage);

        return view('livewire.users.user-list', [
            'users' => $users,
        ]);
    }
}
