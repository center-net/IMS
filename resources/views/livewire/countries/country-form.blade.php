<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ __('countries.form_title') }}</h5>
            <form wire:submit.prevent="save">
                <div class="mb-2">
                    <label class="form-label">{{ __('countries.name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2">
                    <label class="form-label">{{ __('countries.iso_code') }}</label>
                    <input type="text" class="form-control @error('iso_code') is-invalid @enderror" wire:model.defer="iso_code">
                    @error('iso_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2">
                    <label class="form-label">{{ __('countries.national_number') }}</label>
                    <input type="text" class="form-control @error('national_number') is-invalid @enderror" wire:model.defer="national_number">
                    @error('national_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-primary" type="submit"><i class="bi-check"></i> {{ $countryId ? __('countries.update') : __('countries.create') }}</button>
                    <button class="btn btn-secondary" type="button" wire:click="cancel"><i class="bi-arrow-counterclockwise"></i> {{ __('countries.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

