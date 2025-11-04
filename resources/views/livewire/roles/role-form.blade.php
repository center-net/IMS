<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">
            {{ $roleId ? __('roles.edit_title') : __('roles.create_title') }}
        </h5>

        {{-- تم إخفاء حقل المعرف، يُولّد تلقائياً من الاسم الظاهر كـ slug --}}

        <div class="mb-3">
            <label class="form-label">{{ __('roles.display_name') }}</label>
            <input type="text" class="form-control @error('display_name') is-invalid @enderror" wire:model.defer="display_name">
            @error('display_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('roles.permissions') }}</label>
            <button type="button" class="btn btn-outline-secondary btn-sm ms-2" wire:click="openPermissionsModal" title="{{ __('roles.permissions') }}">
                <i class="bi-list-check"></i>
            </button>
            <span class="badge bg-info ms-2">المحددة: {{ count($selectedPermissions) }}</span>
            @php
                // Group permissions by module inferred from code name suffix (e.g., view-users => users)
                $groupMap = [
                    'users' => 'صلاحيات المستخدمين',
                    'roles' => 'صلاحيات الأدوار',
                    'permissions' => 'صلاحيات الصلاحيات',
                    'other' => 'أخرى',
                ];
                $grouped = [];
                foreach ($permissions as $perm) {
                    $parts = explode('-', $perm->name);
                    $module = $parts[1] ?? 'other';
                    $grouped[$module][] = $perm;
                }
                // Ensure order of known groups
                $orderedModules = array_keys($groupMap);
                foreach ($grouped as $module => $items) {
                    if (!in_array($module, $orderedModules)) {
                        $orderedModules[] = $module;
                    }
                }
            @endphp

            @if($showPermissionsModal)
                <!-- إزالة طبقة التعتيم: وضع النافذة بشكل ثابت بدون خلفية تغطي الصفحة -->
                <div class="position-fixed top-50 start-50 translate-middle" style="z-index:1050;">
                    <div class="card shadow-lg" style="width: 75vw; max-width: 900px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <strong>{{ __('roles.permissions') }}</strong>
                                <span class="badge bg-secondary">المحددة: {{ count($selectedPermissions) }}</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="closePermissionsModal"><i class="bi-x"></i></button>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="ابحث عن صلاحية..." wire:model.live="permissionSearch" style="max-width: 280px;">
                            </div>
                            <div class="row g-3">
                                @foreach($orderedModules as $module)
                                    @php $items = $grouped[$module] ?? []; @endphp
                                    @if(count($items))
                                        <div class="col-12">
                                            <div class="card border-0">
                                                @php
                                                    $moduleIds = array_map(fn($p) => $p->id, $items);
                                                    $selectedInModule = array_intersect($moduleIds, $selectedPermissions);
                                                    $allSelectedInModule = count($moduleIds) > 0 && count($selectedInModule) === count($moduleIds);
                                                @endphp
                                                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                                                    <strong>{{ $groupMap[$module] ?? ucfirst($module) }}</strong>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:click='toggleModule(@json($moduleIds))'
                                                            {{ $allSelectedInModule ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label">تحديد الكل</label>
                                                    </div>
                                                </div>
                                                <div class="card-body p-2">
                                                    @foreach($items as $perm)
                                                        @php
                                                            $label = optional($perm->translate(app()->getLocale()))->display_name
                                                                ?? ($perm->display_name ?? $perm->name);
                                                        @endphp
                                                        <div class="form-check form-check-inline me-3 mb-2">
                                                            <input class="form-check-input" type="checkbox" wire:model="selectedPermissions" value="{{ $perm->id }}" id="perm-{{ $perm->id }}">
                                                            <label class="form-check-label" for="perm-{{ $perm->id }}">{{ $label }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if(!count($permissions))
                                    <div class="col-12 text-center text-muted">لا توجد نتائج</div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" wire:click="closePermissionsModal"><i class="bi-x"></i> {{ __('roles.cancel') }}</button>
                            <button type="button" class="btn btn-primary" wire:click="applyPermissions"><i class="bi-check"></i> {{ __('roles.save') }}</button>
                        </div>
                    </div>
                </div>
            @endif
            @error('selectedPermissions')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" wire:click="save"><i class="bi-check"></i> {{ __('roles.save') }}</button>
            <button class="btn btn-secondary" wire:click="cancel"><i class="bi-arrow-counterclockwise"></i> {{ __('roles.cancel') }}</button>
        </div>
    </div>
</div>
