<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">{{ __('fiscal_years.form_title') }}</h5>
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label class="form-label">{{ __('fiscal_years.name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.lazy="name" placeholder="{{ __('fiscal_years.name_placeholder') }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('fiscal_years.year') }}</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" wire:model.lazy="year" min="{{ \Illuminate\Support\Carbon::now()->year }}" max="3000" placeholder="YYYY">
                        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('fiscal_years.start_date') }}</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" wire:model.lazy="start_date" min="{{ $year ? \Illuminate\Support\Carbon::create($year,1,1)->toDateString() : '' }}">
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('fiscal_years.end_date') }}</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" wire:model.lazy="end_date" min="{{ $start_date ? \Illuminate\Support\Carbon::parse($start_date)->addDay()->toDateString() : '' }}">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">{{ __('fiscal_years.end_date_hint') }}</div>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light" wire:click="cancel">{{ __('global.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
