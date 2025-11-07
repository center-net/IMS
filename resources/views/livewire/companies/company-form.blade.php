<div>
    <div class="card shadow-sm mt-3">
        <div class="card-header">
            <h6 class="mb-0">{{ __('companies.title') }}</h6>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label class="form-label"><i class="bi-building me-2 text-muted"></i>{{ __('companies.name') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name" placeholder="{{ __('companies.placeholders.name') }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi-telephone me-2 text-muted"></i>{{ __('companies.phone') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone" placeholder="{{ __('companies.placeholders.phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi-geo-alt me-2 text-muted"></i>{{ __('companies.address') }}</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" rows="3" wire:model.defer="address" placeholder="{{ __('companies.placeholders.address') }}"></textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi-percent me-2 text-muted"></i>{{ __('companies.tax_percentage') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-percent"></i></span>
                        <input type="number" step="0.01" class="form-control @error('tax_percentage') is-invalid @enderror" wire:model.defer="tax_percentage" placeholder="{{ __('companies.placeholders.tax_percentage') }}">
                        @error('tax_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi-envelope me-2 text-muted"></i>{{ __('companies.email') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.defer="email" placeholder="{{ __('companies.placeholders.email') }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi-image me-2 text-muted"></i>{{ __('companies.logo') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-image"></i></span>
                        <input type="text" class="form-control @error('logo') is-invalid @enderror" wire:model.defer="logo" placeholder="{{ __('companies.placeholders.logo') }}">
                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" wire:click="cancel"><i class="bi bi-x-circle"></i> {{ __('companies.cancel') }}</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> {{ __('companies.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
