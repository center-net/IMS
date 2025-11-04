<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Role;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserForm extends Component
{
    public $userId = null;
    public $name = '';
    public $username = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $selectedRoles = [];

    protected $listeners = ['editUser' => 'loadUser'];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->userId)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($this->userId)],
            'password' => [$this->userId ? 'nullable' : 'required', 'string', 'min:6'],
            'selectedRoles' => ['array'],
            'selectedRoles.*' => ['integer', 'exists:roles,id'],
        ];
    }

    public function save()
    {
        // نفّذ التحقق خارج try/catch حتى لا يُبتلع ويظهر في الحقول
        $data = $this->validate();
        try {

            if ($this->userId) {
                if (!auth()->user()?->can('edit-users')) {
                    $this->dispatch('notify', type: 'danger', message: __('users.unauthorized'));
                    return;
                }
                $user = User::findOrFail($this->userId);
                // التقط القيم القديمة قبل التعديل (يشمل الاسم حسب اللغة الحالية والأدوار)
                $oldName = $user->name; // قيمة مترجمة للغة الحالية
                $oldRoles = $user->roles()->pluck('name')->toArray();
                $user->name = $data['name'];
                $user->username = $data['username'] ?? $user->username;
                $user->email = $data['email'];
                $user->phone = $data['phone'];
                if (!empty($data['password'])) {
                    $user->password = Hash::make($data['password']);
                }
                $user->save();
                // مزامنة الأدوار المختارة
                $roleNames = Role::whereIn('id', $this->selectedRoles ?? [])->pluck('name')->toArray();
                $user->syncRoles($roleNames);

                // التقط القيم الجديدة بعد الحفظ والمزامنة
                $newName = $user->name; // القيمة المحدثة للغة الحالية
                $newRoles = $user->roles()->pluck('name')->toArray();

                // حدد التغييرات في الاسم والأدوار تحديدًا
                $rolesChanged = (count(array_diff($oldRoles, $newRoles)) > 0) || (count(array_diff($newRoles, $oldRoles)) > 0);
                $nameChanged = ($oldName !== $newName);

                // إذا لم تتغيّر حقول النموذج الرئيسية (لن يسجّل الترايت) لكن تغيّر الاسم أو الأدوار، سجّل يدويًا
                if (!$user->wasChanged() && ($rolesChanged || $nameChanged)) {
                    $oldValues = [];
                    $newValues = [];
                    if ($nameChanged) {
                        $oldValues['name'] = $oldName;
                        $newValues['name'] = $newName;
                    }
                    if ($rolesChanged) {
                        $oldValues['roles'] = $oldRoles;
                        $newValues['roles'] = $newRoles;
                    }

                    try {
                        SystemLog::create([
                            'user_id' => optional(auth()->user())->id,
                            'type' => 'model',
                            'action' => 'update',
                            'model_type' => User::class,
                            'model_id' => $user->id,
                            'old_values' => $oldValues,
                            'new_values' => $newValues,
                            'message' => __('logs.messages.model_update', ['model' => class_basename(User::class)]),
                            'locale' => app()->getLocale(),
                        ]);
                    } catch (\Throwable $e) {
                        // تجاهل أخطاء السجل حتى لا تؤثر على الحفظ
                    }
                } elseif ($rolesChanged && $user->wasChanged()) {
                    // إذا تغيّرت الأدوار بالإضافة لتغيّر حقول النموذج، سجّل تغيّر الأدوار فقط كسجل إضافي بدون تكرار باقي الحقول
                    try {
                        SystemLog::create([
                            'user_id' => optional(auth()->user())->id,
                            'type' => 'model',
                            'action' => 'update',
                            'model_type' => User::class,
                            'model_id' => $user->id,
                            'old_values' => ['roles' => $oldRoles],
                            'new_values' => ['roles' => $newRoles],
                            'message' => __('logs.messages.model_update', ['model' => class_basename(User::class)]),
                            'locale' => app()->getLocale(),
                        ]);
                    } catch (\Throwable $e) {}
                }
                $this->dispatch('notify', type: 'success', message: __('users.updated_success'));
            } else {
                if (!auth()->user()?->can('create-users')) {
                    $this->dispatch('notify', type: 'danger', message: __('users.unauthorized'));
                    return;
                }
                $user = new User();
                $user->name = $data['name'];
                $user->username = $data['username'] ?? null;
                $user->email = $data['email'];
                $user->phone = $data['phone'];
                $user->password = Hash::make($data['password']);
                $user->save();
                // إسناد الأدوار المختارة عند الإنشاء
                $roleNames = Role::whereIn('id', $this->selectedRoles ?? [])->pluck('name')->toArray();
                $user->syncRoles($roleNames);
                $this->dispatch('notify', type: 'success', message: __('users.created'));
            }

            $this->dispatch('userSaved');
            $this->resetForm();
            // مسح أخطاء التحقق بعد الحفظ الناجح
            $this->resetErrorBag();
            $this->resetValidation();
        } catch (\Throwable $e) {
            // أخطاء غير مرتبطة بالتحقق
            $this->dispatch('notify', type: 'danger', message: __('users.save_failed'));
        }
    }

    public function loadUser($id)
    {
        // تنظيف أخطاء التحقق السابقة عند فتح إجراء جديد
        $this->resetErrorBag();
        $this->resetValidation();
        if (!auth()->user()?->can('edit-users')) {
            $this->dispatch('notify', type: 'danger', message: __('users.unauthorized'));
            return;
        }
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->password = '';
        $this->selectedRoles = $user->roles()->pluck('id')->toArray();
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->phone = '';
        $this->password = '';
        $this->selectedRoles = [];
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function cancel()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('notify', type: 'info', message: __('users.cancelled'));
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get();
        return view('livewire.users.user-form', compact('roles'));
    }
}
