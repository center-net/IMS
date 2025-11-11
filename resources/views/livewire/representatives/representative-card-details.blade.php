<div>
    @php($locale = app()->getLocale())
    @if(!$representative)
        <div class="alert alert-warning">{{ __('representatives.empty') }}</div>
    @elseif(!$card)
        <div class="alert alert-info">{{ __('representatives.empty') }}</div>
    @else
        @php(
            $methodLabel = match($card->commission_method) {
                'gross_sales' => __('representatives.commission_method_gross_sales'),
                'profit' => __('representatives.commission_method_profit'),
                'after_collection' => __('representatives.commission_method_after_collection'),
                default => $card->commission_method,
            }
        )
        @php($attachmentsCount = is_array($card->attachments) ? count($card->attachments) : 0)

        

        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">{{ __('representatives.details_title') }}</h6>

                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-view-basic" data-bs-toggle="tab" data-bs-target="#tab-pane-view-basic" type="button" role="tab" aria-controls="tab-pane-view-basic" aria-selected="true">{{ __('representatives.tabs_basic') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-view-contact" data-bs-toggle="tab" data-bs-target="#tab-pane-view-contact" type="button" role="tab" aria-controls="tab-pane-view-contact" aria-selected="false">{{ __('representatives.tabs_contact') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-view-finance" data-bs-toggle="tab" data-bs-target="#tab-pane-view-finance" type="button" role="tab" aria-controls="tab-pane-view-finance" aria-selected="false">{{ __('representatives.tabs_finance') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-view-extra" data-bs-toggle="tab" data-bs-target="#tab-pane-view-extra" type="button" role="tab" aria-controls="tab-pane-view-extra" aria-selected="false">{{ __('representatives.tabs_extra') }}</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-view-basic" role="tabpanel" aria-labelledby="tab-view-basic">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.name') }}:</strong> {{ optional($card->translate($locale))->name ?? optional($representative->translate($locale))->name }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.code') }}:</strong> {{ $card->code ?? $representative->code }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.role') }}:</strong> {{ $card->role }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.branch') }}:</strong> {{ $card->branch }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.status') }}:</strong> {{ $card->status === 'active' ? __('representatives.active') : __('representatives.suspended') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-pane-view-contact" role="tabpanel" aria-labelledby="tab-view-contact">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.phone') }}:</strong> {{ $card->phone }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.email') }}:</strong> {{ $card->email }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-pane-view-finance" role="tabpanel" aria-labelledby="tab-view-finance">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.commission_rate') }}:</strong> {{ $card->commission_rate }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.commission_method') }}:</strong> {{ $methodLabel }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.commission_min') }}:</strong> {{ $card->commission_min }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div><strong>{{ __('representatives.commission_max') }}:</strong> {{ $card->commission_max }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-pane-view-extra" role="tabpanel" aria-labelledby="tab-view-extra">
                        <div class="row g-2">
                            <div class="col-12">
                                <div><strong>{{ __('representatives.notes') }}:</strong></div>
                                <div class="text-muted">{{ optional($card->translate($locale))->notes }}</div>
                            </div>
                            <div class="col-12">
                                <div class="text-muted small">{{ __('global.attachments') }}: {{ $attachmentsCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
