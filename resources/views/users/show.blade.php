<div class="container py-3">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('auth.user_menu_profile') }}</h5>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi-arrow-90deg-left"></i> {{ __('users.cancel') }}
                </a>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-2 border rounded">
                        <strong>{{ __('users.name') }}:</strong>
                        <div class="text-muted">{{ $user->name }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-2 border rounded">
                        <strong>{{ __('users.username') }}:</strong>
                        <div class="text-muted">{{ $user->username }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-2 border rounded">
                        <strong>{{ __('users.email') }}:</strong>
                        <div class="text-muted">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-2 border rounded">
                        <strong>{{ __('users.phone') }}:</strong>
                        <div class="text-muted">{{ $user->phone }}</div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="p-2 border rounded">
                        <strong>{{ __('users.roles') }}:</strong>
                        <div class="text-muted">
                            @php(
                                $roles = collect($user->roles ?? [])
                                    ->map(function ($role) {
                                        return optional($role->translate(app()->getLocale()))->display_name
                                            ?? ($role->display_name ?? $role->name);
                                    })
                                    ->filter()
                                    ->values()
                            )
                            {{ $roles->isNotEmpty() ? $roles->join(', ') : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
