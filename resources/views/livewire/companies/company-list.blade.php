<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('menu.company_settings') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('companies.search') }}" wire:model.live="search" style="max-width: 220px;">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('companies.name') }}</th>
                            <th>{{ __('companies.phone') }}</th>
                            <th>{{ __('companies.email') }}</th>
                            <th>{{ __('companies.tax_percentage') }}</th>
                            <th class="text-end">{{ __('companies.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <td>{{ $company->id }}</td>
                            <td>{{ optional($company->translate(app()->getLocale()))->name ?? $company->name }}</td>
                            <td>{{ $company->phone }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ number_format($company->tax_percentage, 2) }}%</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" wire:click="edit({{ $company->id }})"><i class="bi-pencil"></i> {{ __('companies.edit') }}</button>
                                    <button class="btn btn-outline-danger" wire:click="confirmDelete({{ $company->id }})"><i class="bi-trash"></i> {{ __('companies.delete') }}</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">{{ __('companies.no_records') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $companies->links() }}
            </div>
        </div>
    </div>
</div>

