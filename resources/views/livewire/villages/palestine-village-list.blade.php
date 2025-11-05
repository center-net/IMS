<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">قرى فلسطين</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('villages.search') }}" wire:model.live="search" style="max-width: 220px;">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                                $selectedCity = $cityFilter ? $cities->firstWhere('id', $cityFilter) : null;
                                $selectedCityName = $selectedCity ? (optional($selectedCity->translate(app()->getLocale()))->name ?? $selectedCity->name) : null;
                            @endphp
                            {{ $selectedCityName ?? __('villages.filter_city') }}
                        </button>
                        <div class="dropdown-menu p-2" style="min-width: 260px; max-height: 300px; overflow:auto;">
                            <input type="text" class="form-control form-control-sm mb-2" placeholder="{{ __('villages.search_city') }}" wire:model.live="citySearch">
                            <button type="button" class="dropdown-item {{ $cityFilter ? '' : 'active' }}" wire:click="$set('cityFilter', null)">{{ __('villages.filter_city') }}</button>
                            @foreach($cities as $city)
                                @php $translatedCity = optional($city->translate(app()->getLocale()))->name; @endphp
                                <button type="button" class="dropdown-item {{ $cityFilter == $city->id ? 'active' : '' }}" wire:click="$set('cityFilter', {{ $city->id }})">{{ $translatedCity ?? $city->name }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('villages.name') }}</th>
                            <th>{{ __('villages.city') }}</th>
                            <!-- Removed country and last updated columns -->
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
                            <!-- Removed country cell and last updated cell -->
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
                            <td colspan="4" class="text-center text-muted">{{ __('villages.no_data') }}</td>
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
    </div>
</div>
