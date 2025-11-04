<?php

namespace App\Livewire\Permissions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Permission;

class PermissionList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $pendingDeleteId = null;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['permissionSaved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        if (!auth()->user()?->can('edit-permissions')) {
            $this->dispatch('notify', type: 'danger', message: __('permissions.unauthorized'));
            return;
        }
        $this->dispatch('editPermission', $id);
    }

    public function confirmDelete($id)
    {
        // امنع فتح مودال التأكيد إذا كان المستخدم غير مخوّل بالحذف
        if (!auth()->user()?->can('delete-permissions')) {
            $this->dispatch('notify', type: 'danger', message: __('permissions.unauthorized'));
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
        if (!auth()->user()?->can('delete-permissions')) {
            $this->dispatch('notify', type: 'danger', message: __('permissions.unauthorized'));
            $this->dispatch('hideConfirmModal');
            return;
        }
        try {
            $perm = Permission::find($id);
            if ($perm) {
                $perm->delete();
                $this->dispatch('notify', type: 'success', message: __('permissions.deleted'));
                $this->dispatch('hideConfirmModal');
                $this->resetPage();
            } else {
                $this->dispatch('notify', type: 'warning', message: __('permissions.not_found'));
            }
        } catch (\Throwable $e) {
            $this->dispatch('notify', type: 'danger', message: __('permissions.delete_failed'));
        }
    }

    public function render()
    {
        $query = Permission::query()
            ->when($this->search, function ($q) {
                $q->whereTranslationLike('display_name', "%{$this->search}%");
            })
            ->orderByDesc('id');

        $permissions = $query->paginate($this->perPage);

        return view('livewire.permissions.permission-list', [
            'permissions' => $permissions,
        ]);
    }
}
