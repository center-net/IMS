<div>
    <div class="card mb-3">
        <div class="card-body d-flex gap-2 align-items-center">
            <input type="text" class="form-control" placeholder="{{ __('fiscal_months.search') }}" wire:model.debounce.400ms="search" />
            <select class="form-select" wire:model.live="fiscal_year_id" style="max-width: 200px">
                <option value="">{{ __('fiscal_months.fiscal_year') }}</option>
                @foreach($fiscalYears as $fy)
                    <option value="{{ $fy->id }}">{{ $fy->year }}</option>
                @endforeach
            </select>
            <select class="form-select" wire:model.live="perPage" style="max-width: 140px">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>{{ __('fiscal_months.code') }}</th>
                        <th>{{ __('fiscal_months.name') }}</th>
                        <th>{{ __('fiscal_months.start_date') }}</th>
                        <th>{{ __('fiscal_months.end_date') }}</th>
                        <th>{{ __('fiscal_months.status') }}</th>
                        <th>{{ __('fiscal_months.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($months as $m)
                        <tr>
                            <td>{{ $m->code }}</td>
                            <td>{{ $m->name }}</td>
                            <td>{{ $m->start_date }}</td>
                            <td>{{ $m->end_date }}</td>
                            <td>
                                @can('close-fiscal-months')
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                               id="monthStatus{{ $m->id }}"
                                               @checked($m->status === 'open')
                                               @disabled($m->status === 'closed')
                                               wire:click="closeMonth({{ $m->id }})">
                                        <label class="form-check-label" for="monthStatus{{ $m->id }}">
                                            {{ $m->status === 'closed' ? __('fiscal_months.closed') : __('fiscal_months.open') }}
                                        </label>
                                    </div>
                                @else
                                    @if($m->status === 'open')
                                        <span class="badge bg-success">{{ __('fiscal_months.open') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('fiscal_months.closed') }}</span>
                                    @endif
                                @endcan
                            </td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-outline-primary" wire:click="$dispatch('showFiscalMonthDetails', { id: {{ $m->id }} })">
                                    {{ __('fiscal_months.details') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">{{ __('fiscal_months.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $months->links() }}
        </div>
    </div>
</div>
