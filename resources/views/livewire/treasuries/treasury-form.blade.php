<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1">
                {{ $editing ? __('treasuries.form_edit_title') : __('treasuries.form_add_title') }}
            </h5>
            
        </div>
        <div class="card-body">
            <form wire:submit.prevent="submit">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('treasuries.name') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name" placeholder="{{ __('treasuries.name_placeholder') }}">
                        </div>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">{{ __('treasuries.is_main') }}</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_main" wire:model.live="is_main">
                            <label class="form-check-label" for="is_main">
                                {{ $is_main ? __('treasuries.main_yes') : __('treasuries.main_no') }}
                            </label>
                        </div>
                    </div>

                    @if(!$is_main)
                        <div class="col-12">
                            <label class="form-label">{{ __('treasuries.main_treasuries') }}</label>
                            {{-- عرض اسم الخزنة الرئيسية المختارة أعلى القائمة --}}
                            @php
                                $selectedMain = null;
                                if(!empty($selectedMainTreasuryId)) {
                                    $selectedMain = $mainTreasuries->firstWhere('id', (int) $selectedMainTreasuryId);
                                }
                            @endphp
                            @if($selectedMain)
                                <div class="mb-2">
                                    <span class="badge bg-info">
                                        {{ optional($selectedMain->translate(app()->getLocale()))->name ?? $selectedMain->code }}
                                    </span>
                                </div>
                            @endif
                            <div class="border rounded-3 p-3 bg-light">
                                <select class="form-select @error('selectedMainTreasuryId') is-invalid @enderror" wire:model.live="selectedMainTreasuryId">
                                    <option value="">-- {{ __('treasuries.main_treasuries') }} --</option>
                                    @forelse($mainTreasuries as $mt)
                                        @php $translated = optional($mt->translate(app()->getLocale()))->name; @endphp
                                        <option value="{{ $mt->id }}">{{ $translated ?? $mt->code }}</option>
                                    @empty
                                        <option value="" disabled>{{ __('treasuries.empty') }}</option>
                                    @endforelse
                                </select>
                                @error('selectedMainTreasuryId')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label">{{ __('treasuries.manager_name') }}</label>
                        @php
                            $selectedManager = null;
                            if(!empty($manager_id)) {
                                $selectedManager = $employees->firstWhere('id', (int) $manager_id);
                            }
                        @endphp
                        @if($selectedManager)
                            <div class="mb-2">
                                <span class="badge bg-info">
                                    {{ optional($selectedManager->translate(app()->getLocale()))->name ?? $selectedManager->username }}
                                </span>
                            </div>
                        @endif
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <select class="form-select @error('manager_id') is-invalid @enderror" wire:model.live="manager_id">
                                <option value="">-- {{ __('treasuries.select_manager') }} --</option>
                                @forelse($employees as $emp)
                                    @php $empName = optional($emp->translate(app()->getLocale()))->name ?? $emp->username; @endphp
                                    <option value="{{ $emp->id }}">{{ $empName }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        @error('manager_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        
                    </div>

                    {{-- حالة الخزنة محذوفة من نموذج التعديل حسب الطلب --}}
                </div>

                <div class="mt-3 d-flex align-items-center">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2 me-1"></i> {{ $editing ? __('treasuries.update') : __('treasuries.create') }}
                    </button>
                    <button class="btn btn-outline-secondary ms-2" type="button" wire:click="cancel">
                        <i class="bi bi-x-circle me-1"></i> {{ __('treasuries.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
