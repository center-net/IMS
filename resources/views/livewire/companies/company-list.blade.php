<div>
    @can('view-companies')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('menu.company_settings') }}</h5>
            </div>

            @forelse($companies as $company)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $company->logo ? asset($company->logo) : asset('favicon.ico') }}" alt="{{ optional($company->translate(app()->getLocale()))->name ?? $company->name }}" class="rounded border" style="width:64px;height:64px;object-fit:contain;background:#fff;">
                            <strong>{{ optional($company->translate(app()->getLocale()))->name ?? $company->name }}</strong>
                        </div>
                        @can('edit-companies')
                            @if($editingId === $company->id)
                                <button class="btn btn-sm btn-success" wire:click="save"><i class="bi-check-lg"></i> {{ __('companies.save') }}</button>
                            @else
                                <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $company->id }})"><i class="bi-pencil"></i> {{ __('companies.edit') }}</button>
                            @endif
                        @endcan
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @if($editingId === $company->id)
                                <li class="list-group-item">
                                    <label class="form-label"><i class="bi-building me-2 text-muted"></i>{{ __('companies.name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </li>
                                <li class="list-group-item">
                                    <label class="form-label"><i class="bi-telephone me-2 text-muted"></i>{{ __('companies.phone') }}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </li>
                                <li class="list-group-item">
                                    <label class="form-label"><i class="bi-envelope me-2 text-muted"></i>{{ __('companies.email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.defer="email">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </li>
                                <li class="list-group-item">
                                    <label class="form-label"><i class="bi-percent me-2 text-muted"></i>{{ __('companies.tax_percentage') }}</label>
                                    <input type="number" step="0.01" class="form-control @error('tax_percentage') is-invalid @enderror" wire:model.defer="tax_percentage">
                                    @error('tax_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </li>
                                <li class="list-group-item">
                                    <label class="form-label"><i class="bi-geo-alt me-2 text-muted"></i>{{ __('companies.address') }}</label>
                                    <textarea rows="3" class="form-control @error('address') is-invalid @enderror" wire:model.defer="address"></textarea>
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </li>
                                <li class="list-group-item">
                                    <label class="form-label"><i class="bi-image me-2 text-muted"></i>{{ __('companies.logo') }}</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="file" class="form-control @error('logoFile') is-invalid @enderror" wire:model="logoFile" accept="image/*">
                                        <span>
                                            @if($logoFile)
                                                <img src="{{ $logoFile->temporaryUrl() }}" alt="{{ optional($company->translate(app()->getLocale()))->name ?? $company->name }}" class="rounded border" style="width:64px;height:64px;object-fit:cover;background:#fff;">
                                            @elseif($company->logo)
                                                <img src="{{ asset($company->logo) }}" alt="{{ optional($company->translate(app()->getLocale()))->name ?? $company->name }}" class="rounded border" style="width:64px;height:64px;object-fit:contain;background:#fff;">
                                            @else
                                                <img src="https://via.placeholder.com/64x64?text=Logo" alt="{{ optional($company->translate(app()->getLocale()))->name ?? $company->name }}" class="rounded border" style="width:64px;height:64px;object-fit:contain;background:#fff;">
                                            @endif
                                        </span>
                                    </div>
                                    @error('logoFile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="form-text" wire:loading wire:target="logoFile">{{ __('companies.uploading') }}</div>
                                </li>
                            @else
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi-building me-2 text-muted"></i>{{ __('companies.name') }}</span>
                                    <span class="text-muted">{{ optional($company->translate(app()->getLocale()))->name ?? $company->name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi-telephone me-2 text-muted"></i>{{ __('companies.phone') }}</span>
                                    <span class="text-muted">{{ $company->phone ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi-envelope me-2 text-muted"></i>{{ __('companies.email') }}</span>
                                    <span class="text-muted">{{ $company->email ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi-percent me-2 text-muted"></i>{{ __('companies.tax_percentage') }}</span>
                                    <span class="text-muted">{{ number_format($company->tax_percentage, 2) }}%</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi-geo-alt me-2 text-muted"></i>{{ __('companies.address') }}</span>
                                    <span class="text-muted">{{ optional($company->translate(app()->getLocale()))->address ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi-image me-2 text-muted"></i>{{ __('companies.logo') }}</span>
                                    <span>
                                        @if($company->logo)
                                            <img src="{{ asset($company->logo) }}" alt="{{ optional($company->translate(app()->getLocale()))->name ?? $company->name }}" class="rounded border" style="width:64px;height:64px;object-fit:contain;background:#fff;">
                                        @else
                                            <img src="https://via.placeholder.com/64x64?text=Logo" alt="{{ optional($company->translate(app()->getLocale()))->name ?? $company->name }}" class="rounded border" style="width:64px;height:64px;object-fit:contain;background:#fff;">
                                        @endif
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-3">{{ __('companies.no_records') }}</div>
            @endforelse

            <div>
                {{ $companies->links() }}
            </div>
        </div>
    </div>
    @else
        <div class="alert alert-danger">{{ __('companies.unauthorized') }}</div>
    @endcan
</div>
