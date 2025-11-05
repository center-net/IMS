<div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">المحافظات</h5>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ __('cities.search') }}" wire:model.live="search" style="max-width: 220px;">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('cities.name') }}</th>
                            <th>{{ __('cities.delivery_price') }}</th>
                            <th class="text-end">{{ __('cities.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                        <tr>
                            <td>{{ $city->id }}</td>
                            <td>
                                @php $translatedCity = optional($city->translate(app()->getLocale()))->name; @endphp
                                {{ $translatedCity ?? $city->name }}
                            </td>
                            <td>{{ number_format($city->delivery_price, 2) }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    @can('edit-cities')
                                        <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $city->id }})"><i class="bi-pencil"></i> {{ __('cities.edit') }}</button>
                                    @endcan
                                    @can('delete-cities')
                                        <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $city->id }})"><i class="bi-trash"></i> {{ __('cities.delete') }}</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">{{ __('cities.no_data') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $cities->links() }}
            </div>
        </div>
    </div>
</div>
