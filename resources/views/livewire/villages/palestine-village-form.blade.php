<div>
    @if(auth()->user()?->can('create-villages') || $villageId)
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ __('villages.form_title') }}</h5>
            <form wire:submit.prevent="save">
                <div class="mb-2">
                    <label class="form-label">{{ __('villages.name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2">
                    <label class="form-label">{{ __('villages.city') }}</label>
                    <div class="dropdown w-100">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start @error('city_id') is-invalid @enderror" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                                $selectedCity = $city_id ? $cities->firstWhere('id', $city_id) : null;
                                $selectedCityName = $selectedCity ? (optional($selectedCity->translate(app()->getLocale()))->name ?? $selectedCity->name) : null;
                            @endphp
                            {{ $selectedCityName ?? __('villages.select_city') }}
                        </button>
                        <div class="dropdown-menu p-2 w-100" style="max-height: 300px; overflow:auto;">
                            <input type="text" class="form-control form-control-sm mb-2" placeholder="{{ __('villages.search_city') }}" wire:model.live="citySearch">
                            <button type="button" class="dropdown-item {{ $city_id ? '' : 'active' }}" wire:click="$set('city_id', null)">{{ __('villages.select_city') }}</button>
                            @foreach($cities as $city)
                                @php $translatedCity = optional($city->translate(app()->getLocale()))->name; @endphp
                                <button type="button" class="dropdown-item {{ $city_id == $city->id ? 'active' : '' }}" wire:click="$set('city_id', {{ $city->id }})">{{ $translatedCity ?? $city->name }}</button>
                            @endforeach
                        </div>
                    </div>
                    @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2 mt-3">
                    @if($villageId)
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-check2"></i> {{ __('villages.update') }}
                        </button>
                    @else
                        @can('create-villages')
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-check2"></i> {{ __('villages.create') }}
                            </button>
                        @endcan
                    @endif
                    <button class="btn btn-secondary" type="button" wire:click="cancel"><i class="bi-arrow-counterclockwise"></i> {{ __('villages.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
