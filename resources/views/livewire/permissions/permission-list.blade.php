<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('menu.permissions') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('permissions.search') }}" wire:model.live="search" style="max-width: 220px;">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('permissions.display_name') }}</th>
                            <th class="text-nowrap">{{ __('permissions.updated') }}</th>
                            <th class="text-end">{{ __('permissions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $perm)
                        <tr>
                            <td>{{ $perm->id }}</td>
                            <td>
                                {{ optional($perm->translate(app()->getLocale()))->display_name ?? ($perm->display_name ?? $perm->name) }}
                            </td>
                            <td>{{ $perm->updated_at?->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $perm->id }})"><i class="bi-pencil"></i> {{ __('permissions.edit') }}</button>
                                <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $perm->id }})"><i class="bi-trash"></i> {{ __('permissions.delete') }}</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">{{ __('permissions.no_results') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('permissions.delete_confirm_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">{{ __('permissions.delete_confirm_text') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('permissions.cancel') }}</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">{{ __('permissions.delete') }}</button>
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

