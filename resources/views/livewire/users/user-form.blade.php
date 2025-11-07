<div>
    @if((auth()->user()?->can('create-users') || $userId) && !$hidden)
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
                    <div class="input-group">
                        <input type="password" id="userPasswordInput" class="form-control @error('password') is-invalid @enderror" wire:model.defer="password" placeholder="{{ __('users.password') }}">
                        <button type="button" class="btn btn-outline-secondary" id="togglePasswordBtn" title="{{ __('users.password') }}">
                            <i class="bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
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
                    @if($userId)
                        @can('edit-users')
                            <button class="btn btn-primary" type="submit">
                                <i class="bi-save"></i> {{ __('users.update') }}
                            </button>
                        @endcan
                    @else
                        @can('create-users')
                            <button class="btn btn-primary" type="submit">
                                <i class="bi-save"></i> {{ __('users.create') }}
                            </button>
                        @endcan
                    @endif
                    <button class="btn btn-secondary" type="button" wire:click="cancel"><i class="bi-x-lg"></i> {{ __('users.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    (function() {
        function setupToggle() {
            const input = document.getElementById('userPasswordInput');
            const btn = document.getElementById('togglePasswordBtn');
            const icon = document.getElementById('togglePasswordIcon');
            if (!input || !btn || !icon) return;
            btn.addEventListener('click', function() {
                const isHidden = input.getAttribute('type') === 'password';
                input.setAttribute('type', isHidden ? 'text' : 'password');
                icon.classList.toggle('bi-eye', !isHidden);
                icon.classList.toggle('bi-eye-slash', isHidden);
            });
        }
        document.addEventListener('DOMContentLoaded', setupToggle);
        window.addEventListener('livewire:load', setupToggle);
    })();
</script>
@endpush
