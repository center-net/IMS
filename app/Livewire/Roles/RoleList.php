<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role;

class RoleList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $pendingDeleteId = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['roleSaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        if (!auth()->user()?->can('edit-roles')) {
            $this->dispatch('notify', type: 'danger', message: __('roles.unauthorized'));
            return;
        }
        $this->dispatch('editRole', $id);
    }

    public function confirmDelete($id)
    {
        // امنع فتح مودال التأكيد إذا كان المستخدم غير مخوّل بالحذف
        if (!auth()->user()?->can('delete-roles')) {
            $this->dispatch('notify', type: 'danger', message: __('roles.unauthorized'));
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
        if (!auth()->user()?->can('delete-roles')) {
            $this->dispatch('notify', type: 'danger', message: __('roles.unauthorized'));
            $this->dispatch('hideConfirmModal');
            return;
        }
        try {
            $role = Role::find($id);
            if ($role) {
                $role->delete();
                $this->dispatch('notify', type: 'success', message: __('roles.deleted'));
                $this->dispatch('hideConfirmModal');
                $this->resetPage();
            } else {
                $this->dispatch('notify', type: 'warning', message: __('roles.not_found'));
            }
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('roles.delete_failed'));
        }
    }

    public function render()
    {
        $query = Role::query()
            ->when($this->search, function ($q) {
                $q->whereTranslationLike('display_name', "%{$this->search}%");
            })
            ->orderByDesc('id');

        $roles = $query->paginate($this->perPage);

        return view('livewire.roles.role-list', [
            'roles' => $roles,
        ]);
    }
}
