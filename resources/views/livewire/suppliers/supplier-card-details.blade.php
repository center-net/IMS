<div>
    @php($locale = app()->getLocale())
    @if(!$supplier)
        <div class="alert alert-warning">{{ __('supplier_cards.no_supplier') }}</div>
    @elseif(!$card)
        <div class="alert alert-info">{{ __('supplier_cards.no_card') }}</div>
    @else
        <div class="mb-2 d-flex align-items-center gap-2">
            <span class="badge bg-secondary">{{ $supplier->code }}</span>
            <strong>{{ __('suppliers.name') }}:</strong>
            <span>{{ optional($supplier->translate($locale))->name ?? $supplier->code }}</span>
        </div>

        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">{{ __('supplier_cards.title') }}</h6>

                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-view-basic" data-bs-toggle="tab" data-bs-target="#tab-pane-view-basic" type="button" role="tab" aria-controls="tab-pane-view-basic" aria-selected="true">{{ __('supplier_cards.tabs_basic') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-view-contact" data-bs-toggle="tab" data-bs-target="#tab-pane-view-contact" type="button" role="tab" aria-controls="tab-pane-view-contact" aria-selected="false">{{ __('supplier_cards.tabs_contact') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-view-finance" data-bs-toggle="tab" data-bs-target="#tab-pane-view-finance" type="button" role="tab" aria-controls="tab-pane-view-finance" aria-selected="false">{{ __('supplier_cards.tabs_finance') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-view-bank" data-bs-toggle="tab" data-bs-target="#tab-pane-view-bank" type="button" role="tab" aria-controls="tab-pane-view-bank" aria-selected="false">{{ __('supplier_cards.tabs_bank') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-view-extra" data-bs-toggle="tab" data-bs-target="#tab-pane-view-extra" type="button" role="tab" aria-controls="tab-pane-view-extra" aria-selected="false">{{ __('supplier_cards.tabs_extra') }}</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-view-basic" role="tabpanel" aria-labelledby="tab-view-basic">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.trade_name') }}:</strong> {{ optional($card->translate($locale))->trade_name }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.name') }}:</strong> {{ optional($card->translate($locale))->name }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.tax_number') }}:</strong> {{ $card->tax_number }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.registration_number') }}:</strong> {{ $card->registration_number }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                @php($typeLabel = $card->supplier_type === 'foreign' ? __('supplier_cards.supplier_type_foreign') : __('supplier_cards.supplier_type_local'))
                                <div><strong>{{ __('supplier_cards.supplier_type') }}:</strong> {{ $typeLabel }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('suppliers.code') }}:</strong> {{ $supplier->code }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-pane-view-contact" role="tabpanel" aria-labelledby="tab-view-contact">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.city') }}:</strong> {{ optional($card->city?->translate($locale))->name ?? $card->city?->name }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.village') }}:</strong> {{ optional($card->village?->translate($locale))->name ?? $card->village?->name }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.phone') }}:</strong> {{ $card->phone }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.fax') }}:</strong> {{ $card->fax }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-pane-view-finance" role="tabpanel" aria-labelledby="tab-view-finance">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.default_currency') }}:</strong> {{ optional($card->defaultCurrency?->translate($locale))->name ?? $card->defaultCurrency?->code }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.credit_limit') }}:</strong> {{ $card->credit_limit }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-pane-view-bank" role="tabpanel" aria-labelledby="tab-view-bank">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.bank_name') }}:</strong> {{ $card->bank_name }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.bank_account_number') }}:</strong> {{ $card->bank_account_number }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.iban') }}:</strong> {{ $card->iban }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.beneficiary_name') }}:</strong> {{ $card->beneficiary_name }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('supplier_cards.bank_account_currency') }}:</strong> {{ optional($card->bankAccountCurrency?->translate($locale))->name ?? $card->bankAccountCurrency?->code }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-pane-view-extra" role="tabpanel" aria-labelledby="tab-view-extra">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                @php($isActive = $card->status === 'active')
                                <div><strong>{{ __('supplier_cards.status') }}:</strong>
                                    <span class="badge {{ $isActive ? 'bg-success' : 'bg-warning text-dark' }}">{{ $isActive ? __('supplier_cards.status_active') : __('supplier_cards.status_suspended') }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div><strong>{{ __('supplier_cards.notes') }}:</strong> {{ optional($card->translate($locale))->notes }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                @php($attachments = is_string($card->attachments) ? json_decode($card->attachments, true) : $card->attachments)
                                @php($attachmentsCount = is_array($attachments) ? count($attachments) : 0)
                                <div class="text-muted small">{{ __('supplier_cards.attachments') }}: {{ $attachmentsCount }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="text-muted small">{{ __('global.created_at') }}: {{ optional($card->created_at)->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="text-muted small">{{ __('global.updated_at') }}: {{ optional($card->updated_at)->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="text-muted small">{{ __('global.created_by') }}: {{ optional($card->creator)->name ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
