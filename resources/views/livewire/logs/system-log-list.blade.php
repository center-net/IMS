<div class="card shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">{{ __('menu.logs') }}</h5>
            <div class="btn-group">
                <button wire:click="exportCsv" class="btn btn-sm btn-outline-primary">
                    <i class="bi-filetype-csv me-1"></i> {{ __('logs.export_csv') }}
                </button>
                <button wire:click="exportExcel" class="btn btn-sm btn-outline-success">
                    <i class="bi-file-earmark-excel me-1"></i> {{ __('logs.export_excel') }}
                </button>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-12 col-md-2">
                <input type="text" class="form-control" placeholder="{{ __('logs.filters.search') }}" wire:model.live="search">
            </div>
            <div class="col-6 col-md-2">
                <select class="form-select" wire:model.live="type">
                    <option value="">{{ __('logs.filters.type') }}</option>
                    <option value="auth">{{ __('logs.types.auth') }}</option>
                    <option value="route">{{ __('logs.types.route') }}</option>
                    <option value="model">{{ __('logs.types.model') }}</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <input type="text" class="form-control" placeholder="{{ __('logs.filters.action') }}" wire:model.live="action">
            </div>
            <div class="col-6 col-md-2">
                <select class="form-select" wire:model.live="userId">
                    <option value="">{{ __('logs.filters.user') }}</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name ?? $u->username }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <input type="date" class="form-control" wire:model.live="dateFrom" placeholder="{{ __('logs.filters.date_from') }}">
            </div>
            <div class="col-6 col-md-2">
                <input type="date" class="form-control" wire:model.live="dateTo" placeholder="{{ __('logs.filters.date_to') }}">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>{{ __('logs.table.id') }}</th>
                        <th>{{ __('logs.table.type') }}</th>
                        <th>{{ __('logs.table.action') }}</th>
                        <th>{{ __('logs.table.user') }}</th>
                        <th>{{ __('logs.table.route') }}</th>
                        <th>{{ __('logs.table.method') }}</th>
                        <th>{{ __('logs.table.ip') }}</th>
                        <th>{{ __('logs.table.model') }}</th>
                        <th>{{ __('logs.table.model_id') }}</th>
                        <th>{{ __('logs.table.date') }}</th>
                        <th>{{ __('logs.table.details') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td><span class="badge bg-secondary">{{ __('logs.types.' . $log->type) ?? $log->type }}</span></td>
                            <td><span class="badge bg-info text-dark">{{ __('logs.actions.' . $log->action) ?? $log->action }}</span></td>
                            <td>{{ optional($log->user)->name ?? optional($log->user)->username ?? $log->user_id }}</td>
                            <td class="text-truncate" style="max-width: 200px" title="{{ $log->route }}">{{ $log->route }}</td>
                            <td>{{ $log->method }}</td>
                            <td>{{ $log->ip }}</td>
                            <td class="text-truncate" style="max-width: 160px" title="{{ $log->model_type }}">{{ class_basename($log->model_type) }}</td>
                            <td>{{ $log->model_id }}</td>
                            <td>{{ optional($log->created_at)->format('Y-m-d H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#log-details-{{ $log->id }}"
                                    aria-expanded="false" aria-controls="log-details-{{ $log->id }}">
                                    {{ __('logs.table.details') }}
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse-row">
                            <td colspan="11" class="p-0 border-0">
                                <div class="collapse" id="log-details-{{ $log->id }}">
                                    <div class="border rounded p-3 m-2 bg-light">
                                        <div class="mb-2">
                                            <strong>{{ __('logs.table.message') }}:</strong>
                                            <span class="ms-1">{{ $log->message }}</span>
                                        </div>
                                        @php
                                            $old = $log->old_values ?? [];
                                            $new = $log->new_values ?? [];
                                            $keys = array_unique(array_merge(array_keys($old ?? []), array_keys($new ?? [])));
                                        @endphp
                                        @if($log->type === 'model' && !empty($keys))
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('logs.table.field') }}</th>
                                                            <th>{{ __('logs.table.old') }}</th>
                                                            <th>{{ __('logs.table.new') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($keys as $key)
                                                            <tr>
                                                                <td>{{ $key }}</td>
                                                                <td>
                                                                    @php $ov = $old[$key] ?? null; @endphp
                                                                    {{ is_array($ov) ? json_encode($ov, JSON_UNESCAPED_UNICODE) : (string)($ov ?? '') }}
                                                                </td>
                                                                <td>
                                                                    @php $nv = $new[$key] ?? null; @endphp
                                                                    {{ is_array($nv) ? json_encode($nv, JSON_UNESCAPED_UNICODE) : (string)($nv ?? '') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">{{ __('logs.table.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $logs->links() }}
        </div>
    </div>
</div>
