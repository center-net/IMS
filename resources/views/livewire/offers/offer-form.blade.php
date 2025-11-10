<div>
    @can('create-offers')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ $editing ? __('offers.form_edit_title') : __('offers.form_add_title') }}</h5>
            <form wire:submit.prevent="submit">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('offers.name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">{{ __('offers.code') }}</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" wire:model.defer="code" readonly>
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('offers.price') }}</label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" wire:model.defer="price">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('offers.original_price') }}</label>
                        <input type="number" step="0.01" class="form-control @error('original_price') is-invalid @enderror" wire:model.defer="original_price">
                        @error('original_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('offers.start_date') }}</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" wire:model.defer="start_date">
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('offers.end_date') }}</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" wire:model.defer="end_date">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3 d-flex align-items-center">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2 me-1"></i> {{ $editing ? __('offers.update') : __('offers.create') }}
                    </button>
                    <button class="btn btn-outline-secondary ms-2" type="button" wire:click="cancel">
                        <i class="bi bi-x-circle me-1"></i> {{ __('offers.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
        @if($editing)
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ $editing ? __('offers.form_edit_title') : __('offers.form_add_title') }}</h5>
                <form wire:submit.prevent="submit">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">{{ __('offers.name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('offers.code') }}</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" wire:model.defer="code" readonly>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('offers.price') }}</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" wire:model.defer="price">
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('offers.original_price') }}</label>
                            <input type="number" step="0.01" class="form-control @error('original_price') is-invalid @enderror" wire:model.defer="original_price">
                            @error('original_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('offers.start_date') }}</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" wire:model.defer="start_date">
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('offers.end_date') }}</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" wire:model.defer="end_date">
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-3 d-flex align-items-center">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-check2 me-1"></i> {{ $editing ? __('offers.update') : __('offers.create') }}
                        </button>
                        <button class="btn btn-outline-secondary ms-2" type="button" wire:click="cancel">
                            <i class="bi bi-x-circle me-1"></i> {{ __('offers.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    @endcan
</div>
