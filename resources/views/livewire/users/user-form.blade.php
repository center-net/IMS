<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ $userId ? __('users.edit_user') : __('users.create_user') }}</h5>

            <form wire:submit.prevent="save" class="vstack gap-2">
                <div>
                    <label class="form-label">{{ __('users.name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">{{ __('users.username') }}</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" wire:model.defer="username">
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">{{ __('users.phone') }}</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">{{ __('users.email') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.defer="email">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="form-label">{{ __('users.password') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" wire:model.defer="password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($userId)
                        <small class="text-muted">{{ __('users.password_keep_hint') }}</small>
                    @endif
                </div>

                <div>
                    <label class="form-label">{{ __('users.roles') }}</label>
                    <select class="form-select @error('selectedRoles') is-invalid @enderror" multiple size="6" wire:model.defer="selectedRoles">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name ?? $role->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedRoles')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi-save"></i> {{ $userId ? __('users.update') : __('users.create') }}
                    </button>
                    <button class="btn btn-secondary" type="button" wire:click="cancel"><i class="bi-x-lg"></i> {{ __('users.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
