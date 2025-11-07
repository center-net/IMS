<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="card-title mb-0">
            {{ $roleId ? __('roles.edit_title') : __('roles.create_title') }}
        </h5>
    </div>
    <div class="card-body">

        {{-- تم إخفاء حقل المعرف، يُولّد تلقائياً من الاسم الظاهر كـ slug --}}

        <div class="mb-3">
            <label class="form-label">{{ __('roles.display_name') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                <input type="text" class="form-control @error('display_name') is-invalid @enderror" wire:model.defer="display_name" placeholder="{{ __('roles.display_name') }}">
                @error('display_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('roles.permissions') }}</label>
            <button type="button" class="btn btn-outline-secondary btn-sm ms-2" wire:click="openPermissionsModal" title="{{ __('roles.permissions') }}">
                <i class="bi-list-check"></i>
            </button>
            <span class="badge bg-info ms-2">{{ __('roles.selected_label') }}: {{ count($selectedPermissions) }}</span>
            @php
                // Group permissions by module inferred from code name suffix (e.g., view-users => users)
                $groupMap = [
                    // ضع إعدادات الشركة أولاً
                    'companies' => __('roles.groups.companies'),
                    'users' => __('roles.groups.users'),
                    'roles' => __('roles.groups.roles'),
                    'permissions' => __('roles.groups.permissions'),
                    'logs' => __('roles.groups.logs'),
                    'countries' => __('roles.groups.countries'),
                    'cities' => __('roles.groups.cities'),
                    'villages' => __('roles.groups.villages'),
                    'fiscal-years' => __('roles.groups.fiscal-years'),
                    'fiscal-months' => __('roles.groups.fiscal-months'),
                    'treasuries' => __('roles.groups.treasuries'),
                    'offers' => __('roles.groups.offers'),
                    'main' => __('roles.groups.main'),
                    'other' => __('roles.groups.other'),
                ];
                $grouped = [];
                $allIds = [];
                foreach ($permissions as $perm) {
                    $parts = explode('-', $perm->name);
                    // اعتبر اسم المجموعة هو كامل الجزء بعد الفعل (يضم المركبات مثل fiscal-years, main-treasuries)
                    $module = count($parts) > 1 ? implode('-', array_slice($parts, 1)) : 'other';
                    $grouped[$module][] = $perm;
                    $allIds[] = $perm->id;
                }
                // Ensure order of known groups
                $orderedModules = array_keys($groupMap);
                foreach ($grouped as $module => $items) {
                    if (!in_array($module, $orderedModules)) {
                        // خرائط إلى مجموعات معروفة عند الحاجة
                        if ($module === 'main-treasuries') {
                            $module = 'treasuries';
                        }
                        $orderedModules[] = $module;
                    }
                }
            @endphp

            @if($showPermissionsModal)
                <!-- إزالة طبقة التعتيم: وضع النافذة بشكل ثابت بدون خلفية تغطي الصفحة -->
                <div class="position-fixed top-50 start-50 translate-middle" style="z-index:1050;">
                    <div id="permissionsModalCard" class="card shadow-lg" style="width: 75vw; max-width: 900px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <strong>{{ __('roles.permissions') }}</strong>
                                @php $totalPermissions = count($permissions); $selectedCount = count($selectedPermissions); @endphp
                                <span class="badge bg-secondary">{{ __('roles.selected_label') }}: {{ $selectedCount }} / {{ $totalPermissions }}</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="closePermissionsModal"><i class="bi-x"></i></button>
                        </div>
                        <div id="permissionsModalBody" class="card-body" style="max-height: 70vh; overflow: auto;">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="{{ __('roles.search_placeholder') }}" wire:model.live="permissionSearch" style="max-width: 280px;">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click='selectAll(@json($allIds))'>{{ __('roles.select_all') }}</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click='clearAll(@json($allIds))'>{{ __('roles.clear_all') }}</button>
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
                                                    <div class="d-flex align-items-center gap-2">
                                                        <strong>{{ $groupMap[$module] ?? ucfirst($module) }}</strong>
                                                        <span class="badge bg-secondary">{{ count($selectedInModule) }} / {{ count($moduleIds) }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                wire:click='toggleModule(@json($moduleIds))'
                                                                {{ $allSelectedInModule ? 'checked' : '' }}
                                                            >
                                                            <label class="form-check-label">{{ __('roles.check_all') }}</label>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="toggleCollapseModule('{{ $module }}')">
                                                            @php $isCollapsed = ($collapseAll ?? false) || (($activeModule ?? null) !== null && $activeModule !== $module) || in_array($module, $collapsedModules); @endphp
                                                            <i class="bi-chevron-{{ $isCollapsed ? 'down' : 'up' }}"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @php $collapsed = ($collapseAll ?? false) || (($activeModule ?? null) !== null && $activeModule !== $module) || in_array($module, $collapsedModules); @endphp
                                                @if(!$collapsed)
                                                    <div class="card-body p-2">
                                                        <div class="row g-2">
                                                            <div class="col-12 col-md-6">
                                                                @foreach(array_slice($items, 0, ceil(count($items)/2)) as $perm)
                                                                    @php
                                                                        $label = optional($perm->translate(app()->getLocale()))->display_name
                                                                            ?? ($perm->display_name ?? $perm->name);
                                                                    @endphp
                                                                    <div class="form-check me-3 mb-2">
                                                                        <input class="form-check-input" type="checkbox" wire:model="selectedPermissions" value="{{ $perm->id }}" id="perm-{{ $perm->id }}">
                                                                        <label class="form-check-label" for="perm-{{ $perm->id }}">{{ $label }}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                @foreach(array_slice($items, ceil(count($items)/2)) as $perm)
                                                                    @php
                                                                        $label = optional($perm->translate(app()->getLocale()))->display_name
                                                                            ?? ($perm->display_name ?? $perm->name);
                                                                    @endphp
                                                                    <div class="form-check me-3 mb-2">
                                                                        <input class="form-check-input" type="checkbox" wire:model="selectedPermissions" value="{{ $perm->id }}" id="perm-{{ $perm->id }}">
                                                                        <label class="form-check-label" for="perm-{{ $perm->id }}">{{ $label }}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if(!count($permissions))
                                    <div class="col-12 text-center text-muted">{{ __('roles.no_results') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center gap-2">
                            <div class="text-muted small">
                                <span class="badge bg-secondary">{{ __('roles.selected_label') }}: {{ $selectedCount }} / {{ $totalPermissions }}</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" wire:click="closePermissionsModal"><i class="bi-x"></i> {{ __('roles.cancel') }}</button>
                                <button type="button" class="btn btn-primary" wire:click="applyPermissions"><i class="bi-check"></i> {{ __('roles.save') }}</button>
                            </div>
                        </div>
                    </div>
                    <!-- شريط درجة الدور العمودي مع أزرار الصعود/الهبوط -->
                    <div class="position-fixed" style="top: 50%; right: 12px; transform: translateY(-50%); z-index:1060;">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="permissionsScrollTop" title="{{ __('roles.scroll_top') }}">
                                <i class="bi-chevron-up"></i>
                            </button>
                            <span class="badge bg-primary" style="writing-mode: vertical-rl; text-orientation: mixed;">
                                {{ __('roles.selected_label') }}: {{ $selectedCount }} / {{ $totalPermissions }}
                            </span>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="permissionsScrollBottom" title="{{ __('roles.scroll_bottom') }}">
                                <i class="bi-chevron-down"></i>
                            </button>
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

@push('scripts')
<script>
    (function () {
        function bodyEl() { return document.getElementById('permissionsModalBody'); }
        function scrollToTop() {
            const el = bodyEl();
            if (el) el.scrollTo({ top: 0, behavior: 'smooth' });
        }
        function scrollToBottom() {
            const el = bodyEl();
            if (el) el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' });
        }

        function attach() {
            const up = document.getElementById('permissionsScrollTop');
            const down = document.getElementById('permissionsScrollBottom');
            if (up) up.addEventListener('click', scrollToTop);
            if (down) down.addEventListener('click', scrollToBottom);
        }

        // عند تحميل Livewire أو إعادة عرض المكوّن، نضمن ربط الأحداث
        window.addEventListener('livewire:load', attach);
        document.addEventListener('DOMContentLoaded', attach);
    })();
</script>
@endpush
