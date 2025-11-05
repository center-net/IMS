<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;

class RoleForm extends Component
{
    public $roleId = null;
    public $name = '';
    public $display_name = '';
    public $selectedPermissions = [];
    public $selectedPermissionsBackup = [];
    public $showPermissionsModal = false;
    public $permissionSearch = '';
    public $collapsedModules = [];
    // Collapse all modules by default when opening permissions modal
    public $collapseAll = true;
    // المجموعة النشطة المفتوحة حاليًا في نمط الأكورديون
    public $activeModule = null;

    protected $listeners = ['editRole' => 'loadRole'];

    protected function rules()
    {
        return [
            'display_name' => ['required', 'string', 'max:255'],
            'selectedPermissions' => ['array'],
        ];
    }

    /**
     * Generate a URL-friendly slug from a display name, with transliteration.
     */
    protected function makeSlug(string $text): string
    {
        // Try ICU transliterator if available (better for Arabic -> Latin)
        if (function_exists('transliterator_transliterate')) {
            $latin = transliterator_transliterate('Any-Latin; Latin-ASCII;', $text);
            $slug = Str::slug($latin);
            if ($slug !== '') return $slug;
        }

        // Fallback to iconv transliteration
        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT', $text) ?: $text;
        $slug = Str::slug($ascii);
        if ($slug !== '') return $slug;

        // Final fallback
        $slug = Str::slug($text);
        return $slug !== '' ? $slug : ('role-' . Str::random(6));
    }

    public function save()
    {
        // دع التحقق يفشل ويعرض أخطاء الحقول تلقائياً بدون التقاطه
        $data = $this->validate();
        try {
            $slugName = $this->makeSlug($data['display_name']);

            if ($this->roleId) {
                if (!auth()->user()?->can('edit-roles')) {
                    $this->dispatch('notify', type: 'danger', message: __('roles.unauthorized'));
                    return;
                }
                $role = Role::findOrFail($this->roleId);
                // لا تغيّر المعرف نهائياً عند التعديل
                // حفظ الترجمة للاسم الظاهر حسب اللغة الحالية
                $role->translateOrNew(app()->getLocale())->display_name = $data['display_name'];
                $role->save();
                $permissionIds = $data['selectedPermissions'] ?? [];
                $permissions = Permission::whereIn('id', $permissionIds)->get();
                $role->syncPermissions($permissions);
                $this->dispatch('notify', type: 'success', message: __('roles.updated_success'));
            } else {
                if (!auth()->user()?->can('create-roles')) {
                    $this->dispatch('notify', type: 'danger', message: __('roles.unauthorized'));
                    return;
                }
                $role = Role::create([
                    // أنشئ المعرف كـ slug تلقائياً
                    'name' => $slugName,
                    'guard_name' => config('permission.defaults.guard', 'web'),
                ]);
                // حفظ الترجمة للاسم الظاهر للدور الجديد
                $role->translateOrNew(app()->getLocale())->display_name = $data['display_name'];
                $role->save();
                $permissionIds = $data['selectedPermissions'] ?? [];
                $permissions = Permission::whereIn('id', $permissionIds)->get();
                $role->syncPermissions($permissions);
                $this->dispatch('notify', type: 'success', message: __('roles.created'));
            }

            $this->dispatch('roleSaved');
            $this->resetForm();
            // مسح أخطاء التحقق بعد الحفظ الناجح
            $this->resetErrorBag();
            $this->resetValidation();
        } catch (\Throwable $e) {
            // أي خطأ غير متعلق بالتحقق
            $this->dispatch('notify', type: 'danger', message: __('roles.save_failed'));
        }
    }

    public function loadRole($id)
    {
        // تنظيف أخطاء التحقق السابقة عند فتح إجراء جديد
        $this->resetErrorBag();
        $this->resetValidation();
        if (!auth()->user()?->can('edit-roles')) {
            $this->dispatch('notify', type: 'danger', message: __('roles.unauthorized'));
            return;
        }
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->display_name = $role->display_name;
        $this->selectedPermissions = $role->permissions()->pluck('id')->toArray();
    }

    public function resetForm()
    {
        $this->roleId = null;
        $this->name = '';
        $this->display_name = '';
        $this->selectedPermissions = [];
        $this->showPermissionsModal = false;
        $this->permissionSearch = '';
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('notify', type: 'info', message: __('roles.cancelled'));
    }

    public function render()
    {
        $permissionsQuery = Permission::query();
        if ($this->permissionSearch) {
            $term = $this->permissionSearch;
            $permissionsQuery->where(function ($q) use ($term) {
                $q->whereTranslationLike('display_name', "%{$term}%")
                  ->orWhere('name', 'like', "%{$term}%");
            });
        }
        $permissions = $permissionsQuery->orderBy('name')->get();
        return view('livewire.roles.role-form', [
            'permissions' => $permissions,
        ]);
    }

    public function openPermissionsModal()
    {
        // تنظيف الأخطاء قبل فتح نافذة الصلاحيات
        $this->resetErrorBag();
        $this->resetValidation();
        if (!auth()->user()?->can('edit-roles')) {
            $this->dispatch('notify', type: 'danger', message: __('roles.unauthorized'));
            $this->showPermissionsModal = false;
            return;
        }
        // احتفظ بنسخة من الاختيارات الحالية لاسترجاعها عند الإلغاء
        $this->selectedPermissionsBackup = $this->selectedPermissions;
        // افتراضيًا تكون جميع المجموعات مطوية
        // افتح مجموعة إعدادات الشركة افتراضيًا وأطوِ البقية بمنطق الأكورديون
        $this->collapseAll = false;
        $this->collapsedModules = [];
        $this->activeModule = 'companies';
        $this->showPermissionsModal = true;
    }

    public function closePermissionsModal()
    {
        // استرجاع الاختيارات الأصلية وإلغاء تغييرات الجلسة داخل النافذة
        $this->selectedPermissions = $this->selectedPermissionsBackup;
        $this->selectedPermissionsBackup = [];
        $this->showPermissionsModal = false;
        $this->permissionSearch = '';
        // تنظيف الأخطاء عند إغلاق النافذة
        $this->resetErrorBag();
        $this->resetValidation();
        // إعادة الطيّ الافتراضي
        $this->collapseAll = true;
        $this->collapsedModules = [];
        $this->activeModule = null;
    }

    public function applyPermissions()
    {
        if ($this->roleId && !auth()->user()?->can('edit-roles')) {
            $this->dispatch('notify', type: 'danger', message: __('roles.unauthorized'));
            $this->showPermissionsModal = false;
            $this->permissionSearch = '';
            $this->selectedPermissionsBackup = [];
            return;
        }
        // إذا كان الدور محفوظًا مسبقًا، طبّق التغييرات مباشرة
        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $permissionIds = $this->selectedPermissions ?? [];
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
            $this->dispatch('notify', type: 'success', message: __('roles.updated_success'));
            // حدّث القوائم المستمعة ثم فرّغ نموذج تعديل الدور
            $this->dispatch('roleSaved');
            $this->resetForm();
        }
        // أغلق النافذة وأفرغ البحث والنسخة الاحتياطية (في حال عدم وجود roleId)
        $this->showPermissionsModal = false;
        $this->permissionSearch = '';
        $this->selectedPermissionsBackup = [];
        // إعادة الطيّ الافتراضي بعد الحفظ
        $this->collapseAll = true;
        $this->collapsedModules = [];
        $this->activeModule = null;
    }

    /**
     * Toggle selection for all permissions within a module/group.
     * If all are selected -> deselect all, else select all.
     */
    public function toggleModule(array $modulePermissionIds)
    {
        // Normalize to integers
        $moduleIds = array_map('intval', $modulePermissionIds);
        $current = array_map('intval', $this->selectedPermissions ?? []);

        $alreadySelectedCount = count(array_intersect($moduleIds, $current));
        $allSelected = $alreadySelectedCount === count($moduleIds) && count($moduleIds) > 0;

        if ($allSelected) {
            // Deselect the whole module
            $this->selectedPermissions = array_values(array_diff($current, $moduleIds));
        } else {
            // Select all module permissions
            $this->selectedPermissions = array_values(array_unique(array_merge($current, $moduleIds)));
        }
    }

    /**
     * Toggle selection for all permissions at once (across all modules).
     */
    public function toggleAll(array $allPermissionIds)
    {
        $allIds = array_map('intval', $allPermissionIds);
        $current = array_map('intval', $this->selectedPermissions ?? []);
        $alreadySelectedCount = count(array_intersect($allIds, $current));
        $allSelected = $alreadySelectedCount === count($allIds) && count($allIds) > 0;

        if ($allSelected) {
            // Deselect all
            $this->selectedPermissions = array_values(array_diff($current, $allIds));
        } else {
            // Select all
            $this->selectedPermissions = array_values(array_unique(array_merge($current, $allIds)));
        }
    }

    /** Always select all permissions */
    public function selectAll(array $allPermissionIds)
    {
        $allIds = array_map('intval', $allPermissionIds);
        $current = array_map('intval', $this->selectedPermissions ?? []);
        $this->selectedPermissions = array_values(array_unique(array_merge($current, $allIds)));
    }

    /** Always clear all permissions */
    public function clearAll(array $allPermissionIds)
    {
        $allIds = array_map('intval', $allPermissionIds);
        $current = array_map('intval', $this->selectedPermissions ?? []);
        $this->selectedPermissions = array_values(array_diff($current, $allIds));
    }

    /**
     * Collapse/expand a module group in the permissions UI.
     */
    public function toggleCollapseModule(string $module)
    {
        // نمط أكورديون بسيط: افتح المجموعة الحالية واطوِ البقية من خلال activeModule
        $this->collapseAll = false;
        $this->activeModule = $module;
        // لا حاجة لإدارة قائمة المطويات، فشرط العرض يعتمد على activeModule
        $this->collapsedModules = [];
    }
}
