<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ __('offers.title') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('offers.search') }}" wire:model.live="search" style="max-width: 220px;">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('offers.code') }}</th>
                            <th>{{ __('offers.name') }}</th>
                            <th>{{ __('offers.price') }}</th>
                            <th>{{ __('offers.original_price') }}</th>
                            <th>{{ __('offers.period') }}</th>
                            <th class="text-nowrap">{{ __('offers.updated') }}</th>
                            <th class="text-end">{{ __('offers.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                        <tr>
                            <td>{{ $offer->id }}</td>
                            <td>{{ $offer->code }}</td>
                            <td>
                                @php $translated = optional($offer->translate(app()->getLocale()))->name; @endphp
                                {{ $translated ?? $offer->name }}
                            </td>
                            <td>{{ number_format($offer->price, 2) }}</td>
                            <td>{{ number_format($offer->original_price, 2) }}</td>
                            <td class="text-nowrap">{{ $offer->start_date }} — {{ $offer->end_date }}</td>
                            <td class="text-nowrap">{{ optional($offer->updated_at)->diffForHumans() }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $offer->id }})"><i class="bi-pencil"></i> {{ __('offers.edit') }}</button>
                                    <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $offer->id }})"><i class="bi-trash"></i> {{ __('offers.delete') }}</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">{{ __('offers.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

