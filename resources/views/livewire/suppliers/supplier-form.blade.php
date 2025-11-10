<div>
    @can('create-suppliers')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ $supplierId ? __('suppliers.form_edit_title') : __('suppliers.form_add_title') }}</h5>
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('suppliers.name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    
                </div>

                <div class="mt-3 d-flex align-items-center">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2 me-1"></i> {{ $supplierId ? __('suppliers.update') : __('suppliers.create') }}
                    </button>
                    <button class="btn btn-outline-secondary ms-2" type="button" wire:click="cancel">
                        <i class="bi bi-x-circle me-1"></i> {{ __('suppliers.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
        @if($supplierId)
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ __('suppliers.form_edit_title') }}</h5>
                <form wire:submit.prevent="save">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">{{ __('suppliers.name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        
                    </div>

                    <div class="mt-3 d-flex align-items-center">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-check2 me-1"></i> {{ __('suppliers.update') }}
                        </button>
                        <button class="btn btn-outline-secondary ms-2" type="button" wire:click="cancel">
                            <i class="bi bi-x-circle me-1"></i> {{ __('suppliers.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    @endcan
</div>
