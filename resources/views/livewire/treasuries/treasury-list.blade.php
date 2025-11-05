<div>
    <div class="card mb-3">
        <div class="card-body d-flex gap-2 align-items-center">
            <input type="text" class="form-control" placeholder="{{ __('treasuries.search') }}" wire:model.debounce.400ms="search" />
            <select class="form-select" wire:model.live="perPage" style="max-width: 140px">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>{{ __('treasuries.code') }}</th>
                        <th>{{ __('treasuries.name') }}</th>
                        <th>{{ __('treasuries.is_main') }}</th>
                        <th>{{ __('treasuries.status') }}</th>
                        <th class="text-end">{{ __('treasuries.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($treasuries as $tr)
                        <tr>
                            <td>{{ $tr->code }}</td>
                            <td>{{ optional($tr->translate(app()->getLocale()))->name ?? $tr->code }}</td>
                            <td>
                                @if($tr->is_main)
                                    <span class="badge bg-success">{{ __('treasuries.main_yes') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('treasuries.main_no') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusSwitch{{ $tr->id }}"
                                           @checked($tr->status === 'open')
                                           wire:click="toggleStatus({{ $tr->id }})">
                                    <label class="form-check-label" for="statusSwitch{{ $tr->id }}">
                                        {{ $tr->status === 'open' ? __('treasuries.open') : __('treasuries.closed') }}
                                    </label>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-warning" wire:click="edit({{ $tr->id }})">
                                        <i class="bi bi-pencil-square"></i> {{ __('treasuries.edit') }}
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="return confirm('{{ __('treasuries.delete_confirm') }}')" wire:click="delete({{ $tr->id }})">
                                        <i class="bi bi-trash"></i> {{ __('treasuries.delete') }}
                                    </button>
                                    <button class="btn btn-outline-primary" wire:click="makeMain({{ $tr->id }})" @disabled($tr->is_main)>
                                        <i class="bi bi-patch-check"></i> {{ __('treasuries.set_main') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">{{ __('treasuries.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $treasuries->links() }}
        </div>
    </div>
</div>
