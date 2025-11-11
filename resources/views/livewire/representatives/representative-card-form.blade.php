<div>
    @php($locale = app()->getLocale())
    @php($canEdit = isset($canEdit) ? (bool)$canEdit : (auth()->user()?->can('edit-representatives') ?? false))
    <form wire:submit.prevent="save">
        @unless($canEdit)
            <div class="alert alert-warning mb-2">{{ __('representatives.unauthorized') }}</div>
        @endunless

        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-basic" data-bs-toggle="tab" data-bs-target="#tab-pane-basic" type="button" role="tab" aria-controls="tab-pane-basic" aria-selected="true">{{ __('representatives.tabs_basic') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-contact" data-bs-toggle="tab" data-bs-target="#tab-pane-contact" type="button" role="tab" aria-controls="tab-pane-contact" aria-selected="false">{{ __('representatives.tabs_contact') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-finance" data-bs-toggle="tab" data-bs-target="#tab-pane-finance" type="button" role="tab" aria-controls="tab-pane-finance" aria-selected="false">{{ __('representatives.tabs_finance') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-extra" data-bs-toggle="tab" data-bs-target="#tab-pane-extra" type="button" role="tab" aria-controls="tab-pane-extra" aria-selected="false">{{ __('representatives.tabs_extra') }}</button>
            </li>
        </ul>

        <fieldset @disabled(!$canEdit)>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-pane-basic" role="tabpanel" aria-labelledby="tab-basic">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('representatives.role') }}</label>
                        <input type="text" class="form-control @error('role') is-invalid @enderror" wire:model.defer="role">
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('representatives.branch') }}</label>
                        <input type="text" class="form-control @error('branch') is-invalid @enderror" wire:model.defer="branch">
                        @error('branch')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('representatives.status') }}</label>
                        <select class="form-select @error('status') is-invalid @enderror" wire:model.defer="status">
                            <option value="active">{{ __('representatives.active') }}</option>
                            <option value="suspended">{{ __('representatives.suspended') }}</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('representatives.name') }} ({{ $locale }})</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('representatives.card_name') }}" wire:model.defer="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-pane-contact" role="tabpanel" aria-labelledby="tab-contact">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('representatives.phone') }}</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('representatives.email') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.defer="email">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-pane-finance" role="tabpanel" aria-labelledby="tab-finance">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('representatives.commission_rate') }}</label>
                        <input type="number" step="0.01" class="form-control @error('commission_rate') is-invalid @enderror" wire:model.defer="commission_rate">
                        @error('commission_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('representatives.commission_method') }}</label>
                        <select class="form-select @error('commission_method') is-invalid @enderror" wire:model.defer="commission_method">
                            <option value="gross_sales">{{ __('representatives.commission_method_gross_sales') }}</option>
                            <option value="profit">{{ __('representatives.commission_method_profit') }}</option>
                            <option value="after_collection">{{ __('representatives.commission_method_after_collection') }}</option>
                        </select>
                        @error('commission_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('representatives.commission_min') }}</label>
                        <input type="number" step="0.01" class="form-control @error('commission_min') is-invalid @enderror" wire:model.defer="commission_min">
                        @error('commission_min')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('representatives.commission_max') }}</label>
                        <input type="number" step="0.01" class="form-control @error('commission_max') is-invalid @enderror" wire:model.defer="commission_max">
                        @error('commission_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-pane-extra" role="tabpanel" aria-labelledby="tab-extra">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label">{{ __('representatives.notes') }}</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" rows="3" wire:model.defer="notes"></textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">المرفقات: {{ $attachmentsCount ?? 0 }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">تاريخ الإضافة: {{ $created_at ?? '—' }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">تاريخ التعديل: {{ $updated_at ?? '—' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">أنشأها: {{ $created_by_name ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
        </fieldset>

        <div class="mt-3 d-flex align-items-center">
            <button class="btn btn-primary" type="submit" @disabled(!$canEdit) wire:loading.attr="disabled" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('representatives.update') }}" aria-label="{{ __('representatives.update') }}">
                <i class="bi bi-check2"></i>
            </button>
            <button class="btn btn-outline-secondary ms-2" type="button" data-bs-dismiss="modal" wire:click="cancel" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('representatives.cancel') }}" aria-label="{{ __('representatives.cancel') }}">
                <i class="bi bi-x-circle"></i>
            </button>
        </div>
    </form>
</div>
