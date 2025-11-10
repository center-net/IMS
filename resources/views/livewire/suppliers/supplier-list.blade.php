<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('suppliers.title') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('suppliers.search') }}" wire:model.live="search" style="max-width: 220px;">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('suppliers.code') }}</th>
                            <th>{{ __('suppliers.name') }}</th>
                            <th>{{ __('supplier_cards.status') }}</th>
                            <th class="text-end">{{ __('suppliers.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->id }}</td>
                            <td>{{ $supplier->code }}</td>
                            <td>{{ optional($supplier->translate(app()->getLocale()))->name ?? $supplier->name }}</td>
                            <td>
                                @php
                                    $status = $supplier->card?->status ?? 'active';
                                    $isActive = $status === 'active';
                                @endphp
                                <div class="form-check form-switch m-0">
                                    @can('edit-suppliers')
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        wire:click="toggleStatus({{ $supplier->id }})"
                                        wire:loading.attr="disabled"
                                        {{ $isActive ? 'checked' : '' }}
                                        aria-label="{{ $isActive ? __('supplier_cards.status_active') : __('supplier_cards.status_suspended') }}"
                                    >
                                    @else
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        {{ $isActive ? 'checked' : '' }}
                                        disabled
                                        aria-label="{{ $isActive ? __('supplier_cards.status_active') : __('supplier_cards.status_suspended') }}"
                                    >
                                    @endcan
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-sm btn-outline-info" wire:click="showDetails({{ $supplier->id }})"
                                            data-bs-target="#supplierDetailsModal"
                                            data-bs-toggle="modal"
                                            data-supplier-name="{{ optional($supplier->translate(app()->getLocale()))->name ?? $supplier->name }}">
                                        <i class="bi bi-card-text" data-bs-toggle="tooltip" title="{{ __('suppliers.details') }}"></i>
                                    </button>
                                    @can('edit-suppliers')
                                    <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $supplier->id }})" data-bs-toggle="tooltip" title="{{ __('suppliers.edit') }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @endcan
                                    @can('delete-suppliers')
                                    <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $supplier->id }})" data-bs-toggle="tooltip" title="{{ __('suppliers.delete') }}">
                                        <i class="bi-trash"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">{{ __('suppliers.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $suppliers->links() }}
            </div>
            <!-- Supplier Card Modal -->
            <div class="modal fade" id="supplierDetailsModal" tabindex="-1" aria-labelledby="supplierDetailsModalLabel" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            @php
                                $selectedSupplier = $selectedSupplierId ? \App\Models\Supplier::with('translations')->find($selectedSupplierId) : null;
                                $selectedSupplierName = $selectedSupplier ? (optional($selectedSupplier->translate(app()->getLocale()))->name ?? $selectedSupplier->name) : null;
                            @endphp
                            <h5 class="modal-title d-flex align-items-center gap-2" id="supplierDetailsModalLabel">
                                <span>{{ __('suppliers.details_title') }}</span>
                                @if($selectedSupplierName)
                                    <span class="text-muted">— {{ $selectedSupplierName }}</span>
                                @endif
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if($selectedSupplierId)
                                @php($canEdit = auth()->user()?->can('edit-suppliers'))
                                <livewire:suppliers.supplier-card-form :supplier-id="$selectedSupplierId" :can-edit="$canEdit" :wire:key="'supplier-card-form-' . $selectedSupplierId" />
                            @else
                                <div class="alert alert-info">{{ __('suppliers.select_supplier') }}</div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('suppliers.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // لا حاجة لحقن الاسم يدويًا بعد تضمين مكوّن بطاقة المورد الذي يعرض الاسم
            </script>
            @if($pendingDeleteId)
                <div class="alert alert-warning mt-3 d-flex justify-content-between align-items-center">
                    <div>{{ __('suppliers.delete_confirm') }}</div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-danger" wire:click="delete"><i class="bi-trash"></i> {{ __('suppliers.delete') }}</button>
                        <button class="btn btn-sm btn-secondary" wire:click="$set('pendingDeleteId', null)">{{ __('suppliers.cancel') }}</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
