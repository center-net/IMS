<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ __('cities.form_title') }}</h5>
            <form wire:submit.prevent="save">
                <div class="mb-2">
                    <label class="form-label">{{ __('cities.name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2">
                    <label class="form-label">{{ __('cities.country') }}</label>
                    @php
                        $selectedCountry = collect($countries)->firstWhere('id', $country_id ?? null);
                        $selectedCountryName = $selectedCountry ? (optional($selectedCountry->translate(app()->getLocale()))->name ?? $selectedCountry->name) : __('cities.select_country');
                    @endphp
                    <div class="dropdown w-100">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi-geo-alt"></i> {{ $selectedCountryName }}
                        </button>
                        <div class="dropdown-menu p-2 w-100" style="max-width: 100%;">
                            <input type="text" class="form-control form-control-sm mb-2" placeholder="{{ __('cities.search') }}" oninput="filterDropdownItems(this, 'countrySelectMenu')">
                            <div id="countrySelectMenu" class="list-group" style="max-height: 250px; overflow:auto;">
                                <button type="button" class="list-group-item list-group-item-action" wire:click="$set('country_id', '')">{{ __('cities.select_country') }}</button>
                                @foreach($countries as $country)
                                    @php $translated = optional($country->translate(app()->getLocale()))->name; @endphp
                                    <button type="button" class="list-group-item list-group-item-action" data-label="{{ $translated ?? $country->name }}" wire:click="$set('country_id', {{ $country->id }})">{{ $translated ?? $country->name }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2">
                    <label class="form-label">{{ __('cities.delivery_price') }}</label>
                    <input type="number" step="0.01" class="form-control @error('delivery_price') is-invalid @enderror" wire:model.defer="delivery_price">
                    @error('delivery_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            <div class="d-flex gap-2 mt-3">
                @if($cityId)
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2"></i> {{ __('cities.update') }}
                    </button>
                @else
                    @can('create-cities')
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-check2"></i> {{ __('cities.create') }}
                        </button>
                    @endcan
                @endif
                <button class="btn btn-secondary" type="button" wire:click="cancel"><i class="bi-arrow-counterclockwise"></i> {{ __('cities.cancel') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
