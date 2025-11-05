<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">نموذج المحافظة (فلسطين)</h5>
            <form wire:submit.prevent="save">
                <div class="mb-2">
                    <label class="form-label">{{ __('cities.name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2">
                    <label class="form-label">{{ __('cities.delivery_price') }}</label>
                    <input type="number" step="0.01" class="form-control @error('delivery_price') is-invalid @enderror" wire:model.defer="delivery_price">
                    @error('delivery_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2 text-muted">
                    <i class="bi-flag"></i>
                    الدولة: <span class="badge bg-light text-dark">{{ optional($country?->translate(app()->getLocale()))->name ?? ($country?->name ?? 'فلسطين') }}</span>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2"></i> {{ $cityId ? __('cities.update') : __('cities.create') }}
                    </button>
                    <button class="btn btn-secondary" type="button" wire:click="cancel"><i class="bi-arrow-counterclockwise"></i> {{ __('cities.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

