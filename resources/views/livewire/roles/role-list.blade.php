<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('menu.roles') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('roles.search') }}" wire:model.live="search" style="max-width: 220px;">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('roles.display_name') }}</th>
                            <th>{{ __('roles.permissions') }}</th>
                            <th class="text-nowrap">{{ __('roles.users_count') }}</th>
                            <th class="text-end">{{ __('roles.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>
                                @php
                                    $translated = optional($role->translate(app()->getLocale()))->display_name;
                                @endphp
                                {{ $translated ?? ($role->display_name ?? $role->name) }}
                            </td>
                            <td>{{ $role->permissions()->count() }}</td>
                            <td>{{ number_format($role->users_count ?? 0) }}</td>
                            <td class="text-end">
                                @can('edit-roles')
                                    <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $role->id }})"><i class="bi-pencil"></i> {{ __('roles.edit') }}</button>
                                @endcan
                                @can('delete-roles')
                                    <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $role->id }})"><i class="bi-trash"></i> {{ __('roles.delete') }}</button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">{{ __('roles.no_results') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                {{ $roles->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('roles.delete_confirm_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">{{ __('roles.delete_confirm_text') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('roles.cancel') }}</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">{{ __('roles.delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            const modalEl = document.getElementById('confirmDeleteModal');
            let modalInstance = null;

            function ensureModal() {
                if (!modalInstance && modalEl) modalInstance = new bootstrap.Modal(modalEl);
                return modalInstance;
            }
            window.addEventListener('showConfirmModal', () => {
                const m = ensureModal();
                if (m) m.show();
            });
            window.addEventListener('hideConfirmModal', () => {
                const m = ensureModal();
                if (m) m.hide();
            });
        })();
    </script>
    @endpush
</div>
