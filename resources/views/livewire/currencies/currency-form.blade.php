<div>
    @canany(['create-currencies','edit-currencies'])
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ $currencyId ? __('currencies.form_edit_title') : __('currencies.form_add_title') }}</h5>
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('currencies.name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('currencies.code') }}</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" wire:model.defer="code">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('currencies.symbol') }}</label>
                        <input type="text" class="form-control @error('symbol') is-invalid @enderror" wire:model.defer="symbol">
                        @error('symbol')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- تم إزالة حقل سعر الصرف مقابل الدولار (يُجلب الآن مباشرة من API) -->
                </div>

                <div class="mt-3 d-flex align-items-center">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2 me-1"></i> {{ $currencyId ? __('currencies.update') : __('currencies.create') }}
                    </button>
                    <button class="btn btn-outline-secondary ms-2" type="button" wire:click="cancel">
                        <i class="bi bi-x-circle me-1"></i> {{ __('currencies.cancel') }}
                    </button>
                </div>
            </form>
            @if(session('message'))
                <div class="alert alert-info mt-3">{{ session('message') }}</div>
            @endif
        </div>
    </div>
    @endcanany
</div>
