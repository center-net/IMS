<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('menu.villages') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('villages.search') }}" wire:model.live="search" style="max-width: 220px;">
                    <select class="form-select form-select-sm" wire:model.live="countryFilter" style="max-width: 200px;">
                        <option value="">{{ __('villages.filter_country') }}</option>
                        @foreach($countries as $country)
                        @php $translated = optional($country->translate(app()->getLocale()))->name; @endphp
                        <option value="{{ $country->id }}">{{ $translated ?? $country->name }}</option>
                        @endforeach
                    </select>
                    <select class="form-select form-select-sm" wire:model.live="cityFilter" style="max-width: 200px;">
                        <option value="">{{ __('villages.filter_city') }}</option>
                        @foreach($cities as $city)
                        @php $translatedCity = optional($city->translate(app()->getLocale()))->name; @endphp
                        <option value="{{ $city->id }}">{{ $translatedCity ?? $city->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('villages.name') }}</th>
                            <th>{{ __('villages.city') }}</th>
                            <th>{{ __('villages.country') }}</th>
                            <!-- تم حذف بند السعر من عرض القرى -->
                            <th class="text-nowrap">{{ __('villages.updated') }}</th>
                            <th class="text-end">{{ __('villages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($villages as $village)
                        <tr>
                            <td>{{ $village->id }}</td>
                            <td>
                                @php $translatedVillage = optional($village->translate(app()->getLocale()))->name; @endphp
                                {{ $translatedVillage ?? $village->name }}
                            </td>
                            <td>
                                @php $translatedCity = optional($village->city?->translate(app()->getLocale()))->name; @endphp
                                {{ $translatedCity ?? $village->city?->name }}
                            </td>
                            <td>
                                @php $translatedCountry = optional($village->city?->country?->translate(app()->getLocale()))->name; @endphp
                                {{ $translatedCountry ?? $village->city?->country?->name }}
                            </td>
                            <!-- تم حذف عرض السعر للقرى -->
                            <td class="text-nowrap">{{ optional($village->updated_at)->diffForHumans() }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    @can('edit-villages')
                                    <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $village->id }})"><i class="bi-pencil"></i> {{ __('villages.edit') }}</button>
                                    @endcan
                                    @can('delete-villages')
                                    <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $village->id }})"><i class="bi bi-trash"></i> {{ __('villages.delete') }}</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">{{ __('villages.empty') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <label class="form-label me-2">{{ __('villages.per_page') }}</label>
                        <select class="form-select form-select-sm d-inline-block w-auto" wire:model.live="perPage">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                    <div>
                        {{ $villages->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Delete Modal (controlled via global events) -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('villages.confirm_delete_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">{{ __('villages.confirm_delete_body') }}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> {{ __('villages.cancel') }}
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">
                            <i class="bi bi-check-lg"></i> {{ __('villages.confirm') }}
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
    </div>
