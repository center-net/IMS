<div>
    <div class="d-flex mb-3 align-items-center gap-2">
        <input type="text" class="form-control" placeholder="{{ __('representatives.search') }}" wire:model.debounce.300ms="search">
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ __('representatives.code') }}</th>
                <th>{{ __('representatives.name') }}</th>
                <th>
                    <button class="btn btn-link p-0 text-decoration-none" type="button" wire:click="sortByField('status')" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('representatives.status') }}" aria-label="{{ __('representatives.status') }}">
                        {{ __('representatives.status') }}
                        @if($sortBy === 'status')
                            <i class="bi bi-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-short"></i>
                        @endif
                    </button>
                </th>
                <th>
                    <button class="btn btn-link p-0 text-decoration-none" type="button" wire:click="sortByField('phone')" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('representatives.phone') }}" aria-label="{{ __('representatives.phone') }}">
                        {{ __('representatives.phone') }}
                        @if($sortBy === 'phone')
                            <i class="bi bi-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-short"></i>
                        @endif
                    </button>
                </th>
                <th class="text-end">{{ __('representatives.actions') }}</th>
            </tr>
        </thead>
        <tbody>
        @forelse($representatives as $r)
            <tr>
                <td>{{ $r->code }}</td>
                <td>{{ $r->translate(app()->getLocale())->name ?? '' }}</td>
                <td>
                    @if($r->card)
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   id="status-switch-{{ $r->id }}"
                                   @checked($r->card->status === 'active')
                                   wire:click="toggleStatus({{ $r->id }})"
                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                   title="{{ __('representatives.' . ($r->card->status ?? 'suspended')) }}"
                                   aria-label="{{ __('representatives.status') }}"
                                   @cannot('edit-representatives') disabled @endcannot>
                        </div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>{{ $r->card?->phone ?? '—' }}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary" wire:click="selectRepresentative({{ $r->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('representatives.details_title') }}" aria-label="{{ __('representatives.details_title') }}">
                        <i class="bi bi-card-text"></i>
                    </button>
                    @can('edit-representatives')
                    <a href="#rep-form" class="btn btn-sm btn-outline-secondary" wire:navigate data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('representatives.edit') }}" aria-label="{{ __('representatives.edit') }}">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    @endcan
                    @can('delete-representatives')
                    <button class="btn btn-sm btn-outline-danger" wire:click="deleteRepresentative({{ $r->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('representatives.delete') }}" aria-label="{{ __('representatives.delete') }}">
                        <i class="bi bi-trash"></i>
                    </button>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">{{ __('representatives.empty') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div>
        {{ $representatives->links() }}
    </div>

    <!-- Modal for Representative Card Details -->
    <div class="modal fade @if($selectedRepresentativeId) show @endif" @if($selectedRepresentativeId) style="display:block;" @endif tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('representatives.details_title') }}
                        @php
                            $repName = null;
                            if ($selectedRepresentativeId) {
                                $tmp = \App\Models\Representative::query()->with('translations')->find($selectedRepresentativeId);
                                $repName = $tmp?->translate(app()->getLocale())->name;
                            }
                        @endphp
                        @if($repName)
                            <span class="text-muted">— {{ $repName }}</span>
                        @endif
                    </h5>
                    <button type="button" class="btn-close" wire:click="$set('selectedRepresentativeId', null)"></button>
                </div>
                <div class="modal-body">
                    @if($selectedRepresentativeId)
                        @livewire('representatives.representative-card-form', ['representativeId' => $selectedRepresentativeId], key('rep-card-form-'.$selectedRepresentativeId))
                    @else
                        <div class="alert alert-info">{{ __('representatives.select_representative') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
