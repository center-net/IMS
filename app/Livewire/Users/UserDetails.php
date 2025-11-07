<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UserDetails extends Component
{
    public ?int $userId = null;
    public ?User $user = null;

    protected $listeners = ['showUserDetails' => 'load', 'hideUserDetails' => 'close'];

    public function load(int $id): void
    {
        if (!auth()->user()?->can('view-user-profiles')) {
            $this->dispatch('notify', type: 'danger', message: __('users.unauthorized'));
            return;
        }
        $this->userId = $id;
        $this->user = User::with('roles')->find($id);
        if (!$this->user) {
            $this->dispatch('notify', type: 'warning', message: __('users.not_found'));
            return;
        }
        // أخفِ نموذج الإضافة/التعديل عند عرض التفاصيل
        $this->dispatch('hideUserForm');
    }

    public function close(): void
    {
        $this->userId = null;
        $this->user = null;
        // أعِد إظهار النموذج عند إغلاق التفاصيل
        $this->dispatch('showUserForm');
    }

    public function render()
    {
        return view('livewire.users.user-details');
    }
}

