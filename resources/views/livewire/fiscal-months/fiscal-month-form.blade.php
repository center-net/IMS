<div>
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{ __('fiscal_months.form_title') }}</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">{{ __('fiscal_months.fiscal_year') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                    <select class="form-select" wire:model.live="fiscal_year_id">
                        <option value="">--</option>
                        @foreach($fiscalYears as $fy)
                            <option value="{{ $fy->id }}">{{ $fy->year }}</option>
                        @endforeach
                    </select>
                </div>
                @error('fiscal_year_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('fiscal_months.name') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar4-week"></i></span>
                    <select class="form-select" wire:model.live="selected_month" @disabled(!$fiscal_year_id)>
                        <option value="">{{ __('fiscal_months.select_month') }}</option>
                        @foreach($availableMonths as $num => $label)
                            <option value="{{ $num }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @error('selected_month') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('fiscal_months.start_date') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                    <input type="date" class="form-control @if(!$manual_dates) bg-light @endif" wire:model.live="start_date" @readonly(!$manual_dates)
                           @if($minStart) min="{{ $minStart }}" @endif
                           @if($maxStart) max="{{ $maxStart }}" @endif />
                </div>
                @error('start_date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('fiscal_months.end_date') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                    <input type="date" class="form-control @if(!$manual_dates) bg-light @endif" wire:model.live="end_date" @readonly(!$manual_dates) />
                </div>
                @error('end_date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex align-items-center gap-3">
                <div>
                    <input id="manualDates" type="checkbox" class="form-check-input" wire:model="manual_dates">
                    <label for="manualDates" class="form-check-label">{{ __('fiscal_months.manual_dates') }}</label>
                </div>
                <div class="text-muted">
                    {{ __('fiscal_months.range_preview') }}:
                    @if($start_date && $end_date)
                        <span class="badge bg-secondary">{{ $start_date }} — {{ $end_date }}</span>
                    @else
                        <span class="small">{{ __('fiscal_months.select_month') }}</span>
                    @endif
                </div>
            </div>

            @if($fiscal_year_id)
                @if(empty($availableMonths))
                    <div class="mt-3">
                        <div class="alert alert-warning mb-0">{{ __('fiscal_months.no_available_months') }}</div>
                    </div>
                @else
                    <div class="mt-2">
                        <div wire:loading.class="d-inline" wire:target="fiscal_year_id,selected_month">
                            <span class="text-muted small">{{ __('fiscal_months.loading_months') }}</span>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div class="card-footer d-flex gap-2">
            <button type="button" class="btn btn-light" wire:click="$dispatch('cancelForm')"><i class="bi bi-x-circle"></i> {{ __('global.cancel') }}</button>
            <button type="button" class="btn btn-primary" wire:click="save"><i class="bi bi-check2-circle"></i> {{ __('global.save') }}</button>
        </div>
    </div>
</div>
