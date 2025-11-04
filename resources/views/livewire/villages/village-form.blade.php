<div>
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
                    <select class="form-select @error('city_id') is-invalid @enderror" wire:model.defer="city_id">
                        <option value="">{{ __('villages.select_city') }}</option>
                        @foreach($cities as $city)
                            @php
                                $translatedCity = optional($city->translate(app()->getLocale()))->name;
                                $translatedCountry = optional($city->country?->translate(app()->getLocale()))->name;
                            @endphp
                            <option value="{{ $city->id }}">{{ $translatedCity ?? $city->name }} — {{ $translatedCountry ?? $city->country?->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- تم حذف بند السعر من نموذج القرى -->

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2"></i> {{ $villageId ? __('villages.update') : __('villages.create') }}
                    </button>
                    <button class="btn btn-secondary" type="button" wire:click="cancel"><i class="bi-arrow-counterclockwise"></i> {{ __('villages.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
