<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('menu.cities') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('cities.search') }}" wire:model.live="search" style="max-width: 220px;">

                    <!-- Searchable Country Filter Dropdown -->
                    @php
                        $selectedCountry = collect($countries)->firstWhere('id', $countryFilter ?? null);
                        $selectedCountryName = $selectedCountry ? (optional($selectedCountry->translate(app()->getLocale()))->name ?? $selectedCountry->name) : __('cities.filter_country');
                    @endphp
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 200px;">
                            <i class="bi-geo-alt"></i> {{ $selectedCountryName }}
                        </button>
                        <div class="dropdown-menu p-2" style="width: 260px;">
                            <input type="text" class="form-control form-control-sm mb-2" placeholder="{{ __('cities.search') }}" oninput="filterDropdownItems(this, 'countryFilterMenu')">
                            <div id="countryFilterMenu" class="list-group" style="max-height: 250px; overflow:auto;">
                                <button type="button" class="list-group-item list-group-item-action" wire:click="$set('countryFilter', '')">{{ __('cities.filter_country') }}</button>
                                @foreach($countries as $country)
                                    @php $translated = optional($country->translate(app()->getLocale()))->name; @endphp
                                    <button type="button" class="list-group-item list-group-item-action" data-label="{{ $translated ?? $country->name }}" wire:click="$set('countryFilter', {{ $country->id }})">{{ $translated ?? $country->name }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('cities.name') }}</th>
                            <th>{{ __('cities.country') }}</th>
                            <th>{{ __('cities.delivery_price') }}</th>
                            <th class="text-nowrap">{{ __('cities.updated') }}</th>
                            <th class="text-end">{{ __('cities.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                        <tr>
                            <td>{{ $city->id }}</td>
                            <td>
                                @php $translatedCity = optional($city->translate(app()->getLocale()))->name; @endphp
                                {{ $translatedCity ?? $city->name }}
                            </td>
                            <td>
                                @php $translatedCountry = optional($city->country?->translate(app()->getLocale()))->name; @endphp
                                {{ $translatedCountry ?? $city->country?->name }}
                            </td>
                            <td>{{ number_format($city->delivery_price, 2) }}</td>
                            <td class="text-nowrap">{{ optional($city->updated_at)->diffForHumans() }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    @can('edit-cities')
                                        <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $city->id }})"><i class="bi-pencil"></i> {{ __('cities.edit') }}</button>
                                    @endcan
                                    @can('delete-cities')
                                        <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $city->id }})"><i class="bi bi-trash"></i> {{ __('cities.delete') }}</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">{{ __('cities.empty') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    <label class="form-label me-2">{{ __('cities.per_page') }}</label>
                    <select class="form-select form-select-sm d-inline-block w-auto" wire:model.live="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div>
                    {{ $cities->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal (controlled via global events) -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('cities.confirm_delete_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">{{ __('cities.confirm_delete_body') }}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> {{ __('cities.cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">
                        <i class="bi bi-check-lg"></i> {{ __('cities.confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterDropdownItems(input, containerId) {
            const term = (input.value || '').toLowerCase();
            const container = document.getElementById(containerId);
            if (!container) return;
            container.querySelectorAll('.list-group-item').forEach(item => {
                const label = (item.getAttribute('data-label') || item.textContent || '').toLowerCase();
                item.style.display = label.includes(term) ? '' : 'none';
            });
        }
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
