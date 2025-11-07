<div>
    @can('create-fiscal-years')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{ __('fiscal_years.form_title') }}</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label class="form-label">{{ __('fiscal_years.name') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.lazy="name" placeholder="{{ __('fiscal_years.name_placeholder') }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('fiscal_years.year') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar2"></i></span>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" wire:model.lazy="year" min="{{ \Illuminate\Support\Carbon::now()->year }}" max="3000" placeholder="{{ __('fiscal_years.year_placeholder') }}">
                        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('fiscal_years.start_date') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" wire:model.lazy="start_date" min="{{ $year ? \Illuminate\Support\Carbon::create($year,1,1)->toDateString() : '' }}">
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('fiscal_years.end_date') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" wire:model.lazy="end_date" min="{{ $start_date ? \Illuminate\Support\Carbon::parse($start_date)->addDay()->toDateString() : '' }}">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light" wire:click="cancel"><i class="bi bi-x-circle"></i> {{ __('global.cancel') }}</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> {{ __('global.save') }}</button>
                </div>
            </form>
        </div>
    </div>
    @endcan
</div>
