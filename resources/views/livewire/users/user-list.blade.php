<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('menu.users') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('users.search') }}" wire:model.live="search" style="max-width: 220px;">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('users.name') }}</th>
                            <th>{{ __('users.username') }}</th>
                            <th>{{ __('users.phone') }}</th>
                            <th>{{ __('users.email') }}</th>
                            <th class="text-nowrap">{{ __('users.updated') }}</th>
                            <th class="text-end">{{ __('users.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->updated_at?->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex flex-nowrap gap-1">
                                    @can('view-user-profiles')
                                        <button class="btn btn-sm btn-outline-info" wire:click="details({{ $user->id }})" data-bs-toggle="tooltip" title="{{ __('users.details') }}">
                                            <i class="bi-person"></i>
                                        </button>
                                    @endcan
                                    @can('change-user-passwords')
                                        @cannot('edit-users')
                                            <button class="btn btn-sm btn-outline-warning" wire:click="changePassword({{ $user->id }})" data-bs-toggle="tooltip" title="{{ __('users.change_password') }}">
                                                <i class="bi-key"></i>
                                            </button>
                                        @endcannot
                                    @endcan
                                    @can('edit-users')
                                        <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $user->id }})" data-bs-toggle="tooltip" title="{{ __('users.edit') }}">
                                            <i class="bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('delete-users')
                                        <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $user->id }})" data-bs-toggle="tooltip" title="{{ __('users.delete') }}">
                                            <i class="bi-trash"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            @php $colspan = 7; @endphp
                            <td colspan="{{ $colspan }}" class="text-center text-muted">{{ __('users.no_results') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('users.change_password') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @can('change-user-passwords')
                        <div class="mb-3">
                            <label class="form-label">{{ __('users.password') }}</label>
                            <input type="password" class="form-control @error('newPassword') is-invalid @enderror" wire:model.defer="newPassword" placeholder="{{ __('users.password') }}">
                            @error('newPassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @else
                        <div class="alert alert-danger mb-0">{{ __('users.unauthorized') }}</div>
                    @endcan
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('users.cancel') }}</button>
                    @can('change-user-passwords')
                        <button type="button" class="btn btn-primary" wire:click="savePassword">{{ __('users.update') }}</button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('users.delete_confirm_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">{{ __('users.delete_confirm_text') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('users.cancel') }}</button>
                    @can('delete-users')
                        <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">{{ __('users.delete') }}</button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            const modalEl = document.getElementById('confirmDeleteModal');
            const pwdModalEl = document.getElementById('changePasswordModal');
            let modalInstance = null;
            let pwdModalInstance = null;
            function initTooltips() {
                const list = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                list.forEach(function (el) {
                    try { new bootstrap.Tooltip(el); } catch (e) {}
                });
            }

            function ensureModal() {
                if (!modalInstance && modalEl) modalInstance = new bootstrap.Modal(modalEl);
                return modalInstance;
            }
            function ensurePwdModal() {
                if (!pwdModalInstance && pwdModalEl) pwdModalInstance = new bootstrap.Modal(pwdModalEl);
                return pwdModalInstance;
            }
            window.addEventListener('showConfirmModal', () => {
                const m = ensureModal();
                if (m) m.show();
            });
            window.addEventListener('hideConfirmModal', () => {
                const m = ensureModal();
                if (m) m.hide();
            });
            window.addEventListener('showPasswordModal', () => {
                const m = ensurePwdModal();
                if (m) m.show();
            });
            window.addEventListener('hidePasswordModal', () => {
                const m = ensurePwdModal();
                if (m) m.hide();
            });
            window.addEventListener('livewire:load', initTooltips);
            document.addEventListener('DOMContentLoaded', initTooltips);
        })();
    </script>
    @endpush
</div>
