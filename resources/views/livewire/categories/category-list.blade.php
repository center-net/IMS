<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('categories.title') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('categories.search') }}" wire:model.live="search" style="max-width: 220px;">
                    @can('create-categories')
                        <button class="btn btn-sm btn-success" wire:click="createRoot" data-bs-toggle="tooltip" title="{{ __('categories.create') }}">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    @endcan
                    @can('delete-categories')
                        <button class="btn btn-sm btn-outline-primary" wire:click="openBulkModal" data-bs-toggle="tooltip" title="{{ __('categories.bulk_title') }}">
                            <i class="bi bi-arrow-left-right"></i>
                        </button>
                    @endcan
                </div>
            </div>

                <ul class="list-group">
                @forelse($roots as $root)
                    @include('livewire.categories._node', ['node' => $root, 'level' => 0, 'openCategoryIds' => $openCategoryIds, 'highlightId' => $highlightId])
                @empty
                    <li class="list-group-item text-center text-muted">{{ __('categories.empty') }}</li>
                @endforelse
                </ul>

            <div class="modal fade" id="category-delete-modal" tabindex="-1" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('categories.delete_confirm_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if($pendingDeleteId)
                                <div class="vstack gap-2">
                                    <div class="alert alert-primary d-flex align-items-center gap-2">
                                        <i class="bi bi-info-circle"></i>
                                        <span>{{ __('categories.delete_confirm') }}</span>
                                    </div>
                                    @if($deleteChildrenCount > 0)
                                        <div class="alert alert-danger d-flex align-items-center gap-2">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            <span>{{ __('categories.delete_warning_children') }}</span>
                                        </div>
                                    @endif
                                    @if($deleteItemsCount > 0)
                                        <div class="alert alert-danger d-flex align-items-center gap-2">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            <span>{{ __('categories.delete_warning_items') }}</span>
                                        </div>
                                    @endif

                                    @if($deleteChildrenCount > 0 || $deleteItemsCount > 0)
                                        <div class="vstack gap-2">
                                            <label class="form-label">{{ __('categories.transfer_select_label') }}</label>
                                            @php($selected = $deleteTargetId ? $deleteOptions->firstWhere('id', $deleteTargetId) : null)
                                            <div class="dropdown w-100">
                                                <button class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                    <span class="text-truncate">{{ $selected ? (optional($selected->translate(app()->getLocale()))->name ?? optional($selected->translate('en'))->name ?? optional($selected->translate('ar'))->name ?? '') : '— ' . __('categories.transfer_select_label') . ' —' }}</span>
                                                    <i class="bi bi-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu p-2 w-100" style="max-height: 320px; overflow:auto; min-width: 100%;">
                                                    <div class="input-group mb-2">
                                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                        <input type="text" class="form-control" placeholder="{{ __('categories.search') }}" wire:model.debounce.300ms="deleteSearch">
                                                    </div>
                                                    <div class="list-group list-group-flush">
                                                        @foreach($deleteOptions as $opt)
                                                            @php($disabled = ($opt->id == $pendingDeleteId) || in_array($opt->id, $deleteDescendantIds ?? []))
                                                            <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" @if($disabled) disabled @endif wire:click="$set('deleteTargetId', {{ $opt->id }})">
                                                                <span class="text-truncate">{{ optional($opt->translate(app()->getLocale()))->name ?? optional($opt->translate('en'))->name ?? optional($opt->translate('ar'))->name ?? '' }}</span>
                                                                @if($disabled)
                                                                    <span class="badge bg-secondary">{{ __('categories.cancel') }}</span>
                                                                @endif
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <small class="text-muted">{{ __('categories.search') }}: {{ $deleteOptions->count() }}</small>
                                                        <button type="button" class="btn btn-primary" wire:click.prevent="transferAndDelete" wire:loading.attr="disabled" wire:target="transferAndDelete" @if(!$deleteTargetId) disabled @endif>
                                                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" wire:loading wire:target="transferAndDelete"></span>
                                                            <i class="bi bi-arrow-left-right"></i> {{ __('categories.confirm_transfer_delete') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="vstack gap-2">
                                            <div class="alert alert-warning d-flex align-items-center gap-2">
                                                <i class="bi bi-exclamation-diamond"></i>
                                                <span>{{ __('categories.delete_cascade_transfer_title') }}</span>
                                            </div>
                                            <button type="button" class="btn btn-outline-warning" wire:click="deleteCascadeTransferToDefault">
                                                <i class="bi bi-shuffle"></i> {{ __('categories.delete_cascade_transfer_action') }}
                                            </button>
                                            @if(!empty($deleteDescendantIds))
                                                <div class="alert alert-light border">
                                                    <div class="fw-bold mb-2">{{ __('categories.delete_subcategories_list_title') }}</div>
                                                    <ul class="small mb-0">
                                                        @foreach($deleteDescendantIds as $sid)
                                                            @php($c = $deleteOptions->firstWhere('id', $sid))
                                                            <li>{{ optional(optional($c)->translate(app()->getLocale()))->name ?? optional(optional($c)->translate('en'))->name ?? '' }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                        <hr/>
                                        <div class="vstack gap-2">
                                            <div class="alert alert-danger d-flex align-items-center gap-2">
                                                <i class="bi bi-x-octagon"></i>
                                                <span>{{ __('categories.delete_cascade_purge_title') }}</span>
                                            </div>
                                            <div class="text-danger small">{{ __('categories.purge_irreversible_warning') }}</div>
                                            <button type="button" class="btn btn-danger" wire:click="deleteCascadePurgeAll">
                                                <i class="bi bi-trash"></i> {{ __('categories.delete_cascade_purge_action') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            @if(($deleteChildrenCount > 0 || $deleteItemsCount > 0))
                                <button type="button" class="btn btn-primary" wire:click="transferAndDelete" @if(!$deleteTargetId) disabled @endif>{{ __('categories.confirm_transfer_delete') }}</button>
                            @else
                                <button type="button" class="btn btn-danger" wire:click="delete">{{ __('categories.confirm_delete') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="category-bulk-transfer-modal" tabindex="-1" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('categories.bulk_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="vstack gap-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control" placeholder="{{ __('categories.search') }}" wire:model.debounce.300ms="bulkSearch">
                                </div>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('categories.bulk_source_label') }}</label>
                                        @php($selectedSrc = $bulkSourceId ? $this->bulkSourceOptions()->firstWhere('id', $bulkSourceId) : null)
                                        <div class="dropdown w-100">
                                            <button class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                <span class="text-truncate">{{ $selectedSrc ? (optional($selectedSrc->translate(app()->getLocale()))->name ?? optional($selectedSrc->translate('en'))->name ?? optional($selectedSrc->translate('ar'))->name ?? '') : '— ' . __('categories.bulk_source_label') . ' —' }}</span>
                                                <i class="bi bi-caret-down"></i>
                                            </button>
                                            <div class="dropdown-menu p-2 w-100" style="max-height: 320px; overflow:auto; min-width: 100%;">
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                    <input type="text" class="form-control" placeholder="{{ __('categories.search') }}" wire:model.debounce.300ms="bulkSourceSearch">
                                                </div>
                                                <div class="list-group list-group-flush">
                                                    @foreach($this->bulkSourceOptions() as $opt)
                                                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" wire:click="$set('bulkSourceId', {{ $opt->id }})">
                                                            <span class="text-truncate">{{ optional($opt->translate(app()->getLocale()))->name ?? optional($opt->translate('en'))->name ?? optional($opt->translate('ar'))->name ?? '' }}</span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('categories.bulk_target_label') }}</label>
                                        @php($selectedTgt = $bulkTargetId ? $this->bulkTargetOptions()->firstWhere('id', $bulkTargetId) : null)
                                        <div class="dropdown w-100">
                                            <button class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                <span class="text-truncate">{{ $selectedTgt ? (optional($selectedTgt->translate(app()->getLocale()))->name ?? optional($selectedTgt->translate('en'))->name ?? optional($selectedTgt->translate('ar'))->name ?? '') : '— ' . __('categories.bulk_target_label') . ' —' }}</span>
                                                <i class="bi bi-caret-down"></i>
                                            </button>
                                            <div class="dropdown-menu p-2 w-100" style="max-height: 320px; overflow:auto; min-width: 100%;">
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                    <input type="text" class="form-control" placeholder="{{ __('categories.search') }}" wire:model.debounce.300ms="bulkTargetSearch">
                                                </div>
                                                <div class="list-group list-group-flush">
                                                    @foreach($this->bulkTargetOptions() as $opt)
                                                        @php($disabled = ($bulkSourceId && $opt->id == $bulkSourceId) || (!empty($bulkDescendantIds) && in_array($opt->id, $bulkDescendantIds)))
                                                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" @if($disabled) disabled @endif wire:click="$set('bulkTargetId', {{ $opt->id }})">
                                                            <span class="text-truncate">{{ optional($opt->translate(app()->getLocale()))->name ?? optional($opt->translate('en'))->name ?? optional($opt->translate('ar'))->name ?? '' }}</span>
                                                            @if($disabled)
                                                                <span class="badge bg-secondary">{{ __('categories.cancel') }}</span>
                                                            @endif
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info d-flex align-items-center gap-2">
                                    <i class="bi bi-arrow-left-right"></i>
                                    <span>{{ __('categories.bulk_hint') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="me-auto">
                                @if($bulkConfirming)
                                    <div class="alert alert-warning mb-0 d-flex align-items-center gap-2">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <span>{{ __('categories.bulk_confirm_stage_message') }}</span>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('categories.cancel') }}</button>
                            @if(!$bulkConfirming)
                                <button type="button" class="btn btn-primary" wire:click.prevent="confirmBulk" @if(!$bulkSourceId || !$bulkTargetId || ($bulkSourceId && $bulkTargetId && $bulkSourceId === $bulkTargetId)) disabled @endif>
                                    <i class="bi bi-check2"></i> {{ __('categories.bulk_confirm') }}
                                </button>
                            @else
                                <button type="button" class="btn btn-danger" wire:click.prevent="bulkTransferAndDelete" wire:loading.attr="disabled" wire:target="bulkTransferAndDelete">
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" wire:loading wire:target="bulkTransferAndDelete"></span>
                                    <i class="bi bi-trash"></i> {{ __('categories.bulk_execute') }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary" wire:click="$set('bulkConfirming', false)">{{ __('categories.bulk_cancel_confirm') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- تم حذف مودال إضافة المادة والمكوّن المرتبط به بناءً على طلبك --}}

    @push('scripts')
    <script>
        // Listen for event to open a collapse list by id
        window.addEventListener('openCollapse', function (event) {
            var detail = event.detail || {};
            var targetId = detail.target;
            if (!targetId) return;
            var el = document.getElementById(targetId);
            if (!el) return;
            try {
                var c = bootstrap.Collapse.getInstance(el) || new bootstrap.Collapse(el, { toggle: false });
                c.show();
            } catch (e) {
                // Fallback: add show class
                el.classList.add('show');
            }
            // Also update the toggle button aria-expanded if present
            var btn = document.querySelector('[data-bs-target="#' + targetId + '"]');
            if (btn) {
                btn.setAttribute('aria-expanded', 'true');
            }
        });
        window.addEventListener('openModal', function (event) {
            var detail = event.detail || {};
            var targetId = detail.target;
            if (!targetId) return;
            var el = document.getElementById(targetId);
            if (!el) return;
            setTimeout(function () {
                try {
                    var m = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el, { backdrop: true, keyboard: true });
                    m.show();
                } catch (e) {}
            }, 50);
        });
        window.addEventListener('closeModal', function (event) {
            var detail = event.detail || {};
            var targetId = detail.target;
            if (!targetId) return;
            var el = document.getElementById(targetId);
            if (!el) return;
            try {
                var m = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
                m.hide();
            } catch (e) {}
        });
    </script>
    @endpush
</div>
