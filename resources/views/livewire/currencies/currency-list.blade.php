<div>
    <div class="card shadow-sm">
        <div class="card-body">
            @if(empty($liveRates))
                <div class="alert alert-warning py-2">{{ __('currencies.live_rate_unavailable') }}</div>
            @endif
            @if(!empty($liveRates) && $liveProvider)
                @php(
                    $providerUrl = $liveProvider === 'exchangerate.host' ? 'https://exchangerate.host/' : ($liveProvider === 'open.er-api.com' ? 'https://www.exchangerate-api.com/' : null)
                )
                <div class="mb-2">
                    <span class="badge bg-info text-dark">
                        {{ __('currencies.source') }}:
                        @if($providerUrl)
                            <a href="{{ $providerUrl }}" target="_blank" rel="noopener noreferrer" class="text-dark text-decoration-none">{{ $liveProvider }}</a>
                        @else
                            <span class="text-dark">{{ $liveProvider }}</span>
                        @endif
                    </span>
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('currencies.title') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('currencies.search') }}" wire:model.live="search" style="max-width: 220px;">
                    <select class="form-select form-select-sm" wire:model.live="perPage" style="max-width: 120px">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('currencies.code') }}</th>
                            <th>{{ __('currencies.name') }}</th>
                            <th>{{ __('currencies.symbol') }}</th>
                            <th class="text-nowrap">{{ __('currencies.rate_to_usd') }}</th>
                            <th class="text-end">{{ __('currencies.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currencies as $currency)
                        <tr>
                            <td>{{ $currency->id }}</td>
                            <td>{{ $currency->code }}</td>
                            <td>{{ optional($currency->translate(app()->getLocale()))->name ?? $currency->code }}</td>
                            <td>{{ $currency->symbol }}</td>
                            <td>
                                @php($rate = $liveRates[$currency->code] ?? null)
                                {{ $rate !== null ? number_format((float) $rate, 8) : '-' }}
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    @can('edit-currencies')
                                    <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $currency->id }})" data-bs-toggle="tooltip" title="{{ __('currencies.edit') }}">
                                        <i class="bi-pencil"></i>
                                    </button>
                                    @endcan
                                    @can('delete-currencies')
                                        @if($pendingDeleteId === $currency->id)
                                            <button class="btn btn-sm btn-danger" wire:click="delete" data-bs-toggle="tooltip" title="{{ __('currencies.delete_confirm') }}">
                                                <i class="bi-trash"></i> {{ __('currencies.delete') }}
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" wire:click="$set('pendingDeleteId', null)" title="{{ __('currencies.cancel') }}">
                                                <i class="bi-x"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $currency->id }})" data-bs-toggle="tooltip" title="{{ __('currencies.delete') }}">
                                                <i class="bi-trash"></i>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">{{ __('currencies.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $currencies->links() }}
            </div>

            @if($pendingDeleteId)
                <div class="alert alert-warning mt-3 d-flex justify-content-between align-items-center">
                    <div>{{ __('currencies.delete_confirm') }}</div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-danger" wire:click="delete"><i class="bi-trash"></i> {{ __('currencies.delete') }}</button>
                        <button class="btn btn-sm btn-secondary" wire:click="$set('pendingDeleteId', null)">{{ __('currencies.cancel') }}</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
