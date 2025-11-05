<div>
    <div class="card shadow-sm mt-3">
        <div class="card-header">{{ __('companies.company_form') }}</div>
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('companies.name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('companies.phone') }}</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">{{ __('companies.address') }}</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" rows="3" wire:model.defer="address"></textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('companies.tax_percentage') }}</label>
                        <input type="number" step="0.01" class="form-control @error('tax_percentage') is-invalid @enderror" wire:model.defer="tax_percentage">
                        @error('tax_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('companies.email') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.defer="email">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('companies.logo') }}</label>
                        <input type="text" class="form-control @error('logo') is-invalid @enderror" wire:model.defer="logo" placeholder="/path/to/logo.png">
                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" wire:click="cancel">{{ __('companies.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ $companyId ? __('companies.update') : __('companies.create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

