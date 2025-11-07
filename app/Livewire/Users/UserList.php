<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $pendingDeleteId = null;
    public $passwordUserId = null;
    public $newPassword = '';
    protected $paginationTheme = 'bootstrap';
    // حالة التفويض للوصول إلى قائمة إدارة الموظفين
    public bool $authorized = false;

    protected $listeners = ['userSaved' => '$refresh'];

    public function mount()
    {
        // السماح بالوصول إذا امتلك المستخدم واحدة من صلاحيات العرض/التعديل/الحذف/تغيير كلمة المرور
        $this->authorized = (bool) (
            auth()->user()?->can('view-users') ||
            auth()->user()?->can('edit-users') ||
            auth()->user()?->can('delete-users') ||
            auth()->user()?->can('change-user-passwords') ||
            auth()->user()?->can('create-users')
        );
        if (!$this->authorized) {
            abort(403);
        }
    }

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

    public function details($id)
    {
        if (!auth()->user()?->can('view-user-profiles')) {
            $this->dispatch('notify', type: 'danger', message: __('users.unauthorized'));
            return;
        }
        $this->dispatch('showUserDetails', $id);
    }

    public function changePassword($id)
    {
        if (!auth()->user()?->can('change-user-passwords')) {
            $this->dispatch('notify', type: 'danger', message: __('users.unauthorized'));
            return;
        }
        // جهّز مودال تغيير كلمة المرور فقط
        $this->passwordUserId = $id;
        $this->newPassword = '';
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('showPasswordModal');
    }

    public function savePassword()
    {
        if (!auth()->user()?->can('change-user-passwords')) {
            $this->dispatch('notify', type: 'danger', message: __('users.unauthorized'));
            return;
        }
        $data = $this->validate([
            'newPassword' => ['required', 'string', 'min:6'],
        ]);
        try {
            $user = User::find($this->passwordUserId);
            if (!$user) {
                $this->dispatch('notify', type: 'warning', message: __('users.not_found'));
                return;
            }
            $user->password = Hash::make($data['newPassword']);
            $user->save();
            $this->dispatch('notify', type: 'success', message: __('users.updated_success'));
            $this->dispatch('hidePasswordModal');
            $this->passwordUserId = null;
            $this->newPassword = '';
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('users.save_failed'));
        }
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
