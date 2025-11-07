<div>
    @if($user)
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">{{ __('users.details') }}</h5>
                    <button class="btn btn-sm btn-outline-secondary" wire:click="close"><i class="bi-x-lg"></i> {{ __('users.cancel') }}</button>
                </div>

                <dl class="row mb-0">
                    <dt class="col-4">{{ __('users.name') }}</dt>
                    <dd class="col-8">{{ $user->name }}</dd>

                    <dt class="col-4">{{ __('users.username') }}</dt>
                    <dd class="col-8">{{ $user->username }}</dd>

                    <dt class="col-4">{{ __('users.phone') }}</dt>
                    <dd class="col-8">{{ $user->phone }}</dd>

                    <dt class="col-4">{{ __('users.email') }}</dt>
                    <dd class="col-8">{{ $user->email }}</dd>

                    <dt class="col-4">{{ __('users.roles') }}</dt>
                    <dd class="col-8">
                        @foreach($user->roles as $role)
                            <span class="badge bg-light text-dark me-1">{{ $role->display_name ?? $role->name }}</span>
                        @endforeach
                    </dd>

                    <dt class="col-4">{{ __('users.updated') }}</dt>
                    <dd class="col-8">{{ $user->updated_at?->format('Y-m-d H:i') }}</dd>
                </dl>
            </div>
        </div>
    @endif
</div>
