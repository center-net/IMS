<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('menu.fiscal_years') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('fiscal_years.search') }}" wire:model.live="search" style="max-width: 220px;">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('fiscal_years.code') }}</th>
                            <th>{{ __('fiscal_years.name') }}</th>
                            <th>{{ __('fiscal_years.year') }}</th>
                            <th>{{ __('fiscal_years.start_date') }}</th>
                            <th>{{ __('fiscal_years.end_date') }}</th>
                            <th>{{ __('fiscal_years.status') }}</th>
                            <th class="text-end">{{ __('fiscal_years.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fiscalYears as $fy)
                        <tr>
                            <td>{{ $fy->id }}</td>
                            <td class="text-nowrap">{{ $fy->code }}</td>
                            <td>{{ optional($fy->translate(app()->getLocale()))->name ?? $fy->name }}</td>
                            <td>{{ $fy->year }}</td>
                            <td>{{ $fy->start_date }}</td>
                            <td>{{ $fy->end_date }}</td>
                            <td>
                                @can('close-fiscal-years')
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="fyStatus{{ $fy->id }}"
                                               @checked($fy->status === 'open')
                                               @disabled($fy->status === 'closed')
                                               wire:click="closeYear({{ $fy->id }})">
                                        <label class="form-check-label" for="fyStatus{{ $fy->id }}">
                                            {{ $fy->status === 'open' ? __('fiscal_years.open') : __('fiscal_years.closed') }}
                                        </label>
                                    </div>
                                @else
                                    @if($fy->status === 'closed')
                                        <span class="badge bg-dark">{{ __('fiscal_years.closed') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __('fiscal_years.open') }}</span>
                                    @endif
                                @endcan
                            </td>
                            <td class="text-end text-nowrap">
                <button class="btn btn-sm btn-outline-secondary" wire:click="details({{ $fy->id }})" data-bs-toggle="tooltip" title="{{ __('fiscal_years.details') }}">
                    <i class="bi bi-card-text"></i>
                </button>
                            </td>
                            
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">{{ __('fiscal_years.empty') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    {{ __('fiscal_years.per_page') }}
                    <select class="form-select form-select-sm d-inline-block w-auto" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div>
                    {{ $fiscalYears->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal trigger is handled via events -->
</div>
