<div>
    @php($locale = app()->getLocale())
    @php($canEdit = isset($canEdit) ? (bool)$canEdit : (auth()->user()?->can('edit-suppliers') ?? false))
    <form wire:submit.prevent="save">
        @unless($canEdit)
            <div class="alert alert-warning mb-2">{{ __('suppliers.unauthorized') }}</div>
        @endunless
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-basic" data-bs-toggle="tab" data-bs-target="#tab-pane-basic" type="button" role="tab" aria-controls="tab-pane-basic" aria-selected="true">{{ __('supplier_cards.tabs_basic') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-contact" data-bs-toggle="tab" data-bs-target="#tab-pane-contact" type="button" role="tab" aria-controls="tab-pane-contact" aria-selected="false">{{ __('supplier_cards.tabs_contact') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-finance" data-bs-toggle="tab" data-bs-target="#tab-pane-finance" type="button" role="tab" aria-controls="tab-pane-finance" aria-selected="false">{{ __('supplier_cards.tabs_finance') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-bank" data-bs-toggle="tab" data-bs-target="#tab-pane-bank" type="button" role="tab" aria-controls="tab-pane-bank" aria-selected="false">{{ __('supplier_cards.tabs_bank') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-extra" data-bs-toggle="tab" data-bs-target="#tab-pane-extra" type="button" role="tab" aria-controls="tab-pane-extra" aria-selected="false">{{ __('supplier_cards.tabs_extra') }}</button>
            </li>
        </ul>
        <fieldset @disabled(!$canEdit)>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-pane-basic" role="tabpanel" aria-labelledby="tab-basic">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.trade_name') }}</label>
                        <input type="text" class="form-control @error('trade_name') is-invalid @enderror" wire:model.defer="trade_name">
                        @error('trade_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.tax_number') }}</label>
                        <input type="text" class="form-control @error('tax_number') is-invalid @enderror" wire:model.defer="tax_number">
                        @error('tax_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.registration_number') }}</label>
                        <input type="text" class="form-control @error('registration_number') is-invalid @enderror" wire:model.defer="registration_number">
                        @error('registration_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.supplier_type') }}</label>
                        <select class="form-select @error('supplier_type') is-invalid @enderror" wire:model.defer="supplier_type">
                            <option value="local">{{ __('supplier_cards.supplier_type_local') }}</option>
                            <option value="foreign">{{ __('supplier_cards.supplier_type_foreign') }}</option>
                        </select>
                        @error('supplier_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('suppliers.code') }}</label>
                        <input type="text" class="form-control" value="{{ \App\Models\Supplier::find($supplierId)?->code }}" disabled>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-pane-contact" role="tabpanel" aria-labelledby="tab-contact">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.city') }}</label>
                        <select class="form-select @error('city_id') is-invalid @enderror" wire:model.defer="city_id">
                            <option value="">—</option>
                            @foreach($cities as $city)
                                @php($cname = optional($city->translate($locale))->name ?? $city->name)
                                <option value="{{ $city->id }}">{{ $cname }}</option>
                            @endforeach
                        </select>
                        @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.village') }}</label>
                        <select class="form-select @error('village_id') is-invalid @enderror" wire:model.defer="village_id">
                            <option value="">—</option>
                            @foreach($villages as $village)
                                @php($vname = optional($village->translate($locale))->name ?? $village->name)
                                <option value="{{ $village->id }}">{{ $vname }}</option>
                            @endforeach
                        </select>
                        @error('village_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.phone') }}</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.fax') }}</label>
                        <input type="text" class="form-control @error('fax') is-invalid @enderror" wire:model.defer="fax">
                        @error('fax')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-pane-finance" role="tabpanel" aria-labelledby="tab-finance">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.default_currency') }}</label>
                        <select class="form-select @error('default_currency_id') is-invalid @enderror" wire:model.defer="default_currency_id">
                            <option value="">—</option>
                            @foreach($currencies as $currency)
                                @php($cname = optional($currency->translate($locale))->name ?? $currency->code)
                                <option value="{{ $currency->id }}">{{ $cname }}</option>
                            @endforeach
                        </select>
                        @error('default_currency_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.credit_limit') }}</label>
                        <input type="number" step="0.01" class="form-control @error('credit_limit') is-invalid @enderror" wire:model.defer="credit_limit">
                        @error('credit_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-pane-bank" role="tabpanel" aria-labelledby="tab-bank">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.bank_name') }}</label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" wire:model.defer="bank_name">
                        @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.bank_account_number') }}</label>
                        <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" wire:model.defer="bank_account_number">
                        @error('bank_account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.iban') }}</label>
                        <input type="text" class="form-control @error('iban') is-invalid @enderror" wire:model.defer="iban">
                        @error('iban')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.beneficiary_name') }}</label>
                        <input type="text" class="form-control @error('beneficiary_name') is-invalid @enderror" wire:model.defer="beneficiary_name">
                        @error('beneficiary_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.bank_account_currency') }}</label>
                        <select class="form-select @error('bank_account_currency_id') is-invalid @enderror" wire:model.defer="bank_account_currency_id">
                            <option value="">—</option>
                            @foreach($currencies as $currency)
                                @php($cname = optional($currency->translate($locale))->name ?? $currency->code)
                                <option value="{{ $currency->id }}">{{ $cname }}</option>
                            @endforeach
                        </select>
                        @error('bank_account_currency_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-pane-extra" role="tabpanel" aria-labelledby="tab-extra">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label">{{ __('supplier_cards.status') }}</label>
                        <select class="form-select @error('status') is-invalid @enderror" wire:model.defer="status">
                            <option value="active">{{ __('supplier_cards.status_active') }}</option>
                            <option value="suspended">{{ __('supplier_cards.status_suspended') }}</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('supplier_cards.notes') }}</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" rows="3" wire:model.defer="notes"></textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">{{ __('global.attachments') }}: {{ $attachmentsCount ?? 0 }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">{{ __('global.created_at') }}: {{ $created_at ?? '—' }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">{{ __('global.updated_at') }}: {{ $updated_at ?? '—' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">{{ __('global.created_by') }}: {{ $created_by_name ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
        </fieldset>
        <div class="mt-3 d-flex align-items-center">
            <button class="btn btn-primary" type="submit" @disabled(!$canEdit) wire:loading.attr="disabled">
                <i class="bi bi-check2 me-1"></i> {{ __('suppliers.update') }}
            </button>
            <button class="btn btn-outline-secondary ms-2" type="button" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> {{ __('suppliers.cancel') }}
            </button>
        </div>
    </form>
</div>
