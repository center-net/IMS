<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('menu.countries') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('countries.search') }}" wire:model.live="search" style="max-width: 220px;">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('countries.name') }}</th>
                            <th>{{ __('countries.iso_code') }}</th>
                            <th>{{ __('countries.national_number') }}</th>
                            <th class="text-nowrap">{{ __('countries.updated') }}</th>
                            <th class="text-end">{{ __('countries.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($countries as $country)
                        <tr>
                            <td>{{ $country->id }}</td>
                            <td>
                                @php
                                    $translated = optional($country->translate(app()->getLocale()))->name;
                                @endphp
                                {{ $translated ?? $country->name }}
                            </td>
                            <td>{{ $country->iso_code }}</td>
                            <td>{{ $country->national_number }}</td>
                            <td class="text-nowrap">{{ optional($country->updated_at)->diffForHumans() }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    @can('edit-countries')
                                        <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $country->id }})"><i class="bi-pencil"></i> {{ __('countries.edit') }}</button>
                                    @endcan
                                    @can('delete-countries')
                                        <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $country->id }})"><i class="bi bi-trash"></i> {{ __('countries.delete') }}</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">{{ __('countries.empty') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    <label class="form-label me-2">{{ __('countries.per_page') }}</label>
                    <select class="form-select form-select-sm d-inline-block w-auto" wire:model.live="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div>
                    {{ $countries->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal (controlled via global events) -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('countries.confirm_delete_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">{{ __('countries.confirm_delete_body') }}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> {{ __('countries.cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">
                        <i class="bi bi-check-lg"></i> {{ __('countries.confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('showConfirmModal', () => {
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        });
        window.addEventListener('hideConfirmModal', () => {
            const modalEl = document.getElementById('confirmDeleteModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        });
    </script>
</div>

