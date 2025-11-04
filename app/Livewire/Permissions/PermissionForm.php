<?php

namespace App\Livewire\Permissions;

use Livewire\Component;
use App\Models\Permission;

class PermissionForm extends Component
{
    public $permissionId = null;
    public $display_name = '';
    public $name = '';

    protected $listeners = ['editPermission' => 'loadPermission'];

    protected function rules()
    {
        return [
            'display_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function save()
    {
        // نفّذ التحقق أولاً ودع فشل التحقق يظهر في الحقول
        $data = $this->validate();
        try {

            $slug = $this->makeSlug($data['display_name']);

            if ($this->permissionId) {
                if (!auth()->user()?->can('edit-permissions')) {
                    $this->dispatch('notify', type: 'danger', message: __('permissions.unauthorized'));
                    return;
                }
                $perm = Permission::findOrFail($this->permissionId);
                // لا تغيّر المعرف نهائياً عند التعديل
                $perm->translateOrNew(app()->getLocale())->display_name = $data['display_name'];
                $perm->save();
                $this->dispatch('notify', type: 'success', message: __('permissions.updated_success'));
            } else {
                if (!auth()->user()?->can('create-permissions')) {
                    $this->dispatch('notify', type: 'danger', message: __('permissions.unauthorized'));
                    return;
                }
                $perm = new Permission();
                $perm->name = $slug;
                $perm->guard_name = $perm->guard_name ?? config('auth.defaults.guard', 'web');
                $perm->translateOrNew(app()->getLocale())->display_name = $data['display_name'];
                $perm->save();
                $this->dispatch('notify', type: 'success', message: __('permissions.created'));
            }

            $this->dispatch('permissionSaved');
            $this->resetForm();
            // مسح أخطاء التحقق بعد الحفظ الناجح
            $this->resetErrorBag();
            $this->resetValidation();
        } catch (\Throwable $e) {
            // أخطاء غير تحقق
            $this->dispatch('notify', type: 'danger', message: __('permissions.save_failed'));
        }
    }

    public function loadPermission($id)
    {
        // تنظيف أخطاء التحقق السابقة عند فتح إجراء جديد
        $this->resetErrorBag();
        $this->resetValidation();
        if (!auth()->user()?->can('edit-permissions')) {
            $this->dispatch('notify', type: 'danger', message: __('permissions.unauthorized'));
            return;
        }
        $perm = Permission::findOrFail($id);
        $this->permissionId = $perm->id;
        $this->name = $perm->name;
        $this->display_name = optional($perm->translate(app()->getLocale()))->display_name
            ?? ($perm->display_name ?? $perm->name);
    }

    public function resetForm()
    {
        $this->permissionId = null;
        $this->name = '';
        $this->display_name = '';
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('notify', type: 'info', message: __('permissions.cancelled'));
    }

    public function render()
    {
        return view('livewire.permissions.permission-form');
    }

    private function makeSlug(string $input): string
    {
        $arabicMap = [
            'أ' => 'a', 'ا' => 'a', 'إ' => 'i', 'آ' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th',
            'ج' => 'j', 'ح' => 'h', 'خ' => 'kh', 'د' => 'd', 'ذ' => 'dh', 'ر' => 'r', 'ز' => 'z',
            'س' => 's', 'ش' => 'sh', 'ص' => 's', 'ض' => 'd', 'ط' => 't', 'ظ' => 'z', 'ع' => 'a',
            'غ' => 'gh', 'ف' => 'f', 'ق' => 'q', 'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n',
            'ه' => 'h', 'و' => 'w', 'ي' => 'y', 'ى' => 'a', 'ة' => 'h', 'ء' => '', 'ؤ' => 'w', 'ئ' => 'y',
        ];

        $normalized = strtr($input, $arabicMap);
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $normalized) ?: $normalized;
        $normalized = strtolower($normalized);
        $normalized = preg_replace('/[^a-z0-9]+/i', '-', $normalized);
        $normalized = trim($normalized, '-');
        return $normalized ?: 'permission';
    }
}
