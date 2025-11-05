<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('treasuries.form_title') }}</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="submit">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('treasuries.name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('treasuries.is_main') }}</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_main" wire:model.live="is_main">
                            <label class="form-check-label" for="is_main">
                                {{ $is_main ? __('treasuries.main_yes') : __('treasuries.main_no') }}
                            </label>
                        </div>
                    </div>

                    @if(!$is_main)
                        <div class="col-md-4">
                            <label class="form-label">{{ __('treasuries.main_treasuries') }}</label>
                            <div class="dropdown w-100">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('treasuries.main_treasuries') }}
                                </button>
                                <div class="dropdown-menu p-2 w-100" style="max-height: 300px; overflow:auto;">
                                    @forelse($mainTreasuries as $mt)
                                        @php $translated = optional($mt->translate(app()->getLocale()))->name; @endphp
                                        <span class="dropdown-item text-muted">{{ $translated ?? $mt->code }}</span>
                                    @empty
                                        <span class="dropdown-item disabled">{{ __('treasuries.empty') }}</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($editing)
                        <div class="col-md-4">
                            <label class="form-label">{{ __('treasuries.status') }}</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="statusSwitch"
                                       @checked($status === 'open')
                                       wire:change="$set('status', $event.target.checked ? 'open' : 'closed')">
                                <label class="form-check-label" for="statusSwitch">
                                    {{ $status === 'open' ? __('treasuries.open') : __('treasuries.closed') }}
                                </label>
                            </div>
                            @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <div class="form-text">{{ __('treasuries.status_hint') }}</div>
                        </div>
                    @endif
                </div>

                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2"></i> {{ $editing ? __('treasuries.update') : __('treasuries.create') }}
                    </button>
                    <button class="btn btn-secondary ms-2" type="button" wire:click="cancel">
                        <i class="bi bi-x-circle"></i> {{ __('treasuries.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
