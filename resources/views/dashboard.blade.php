@extends('layouts.app')

@section('title', __('dashboard.title'))

@section('content')
    <div class="py-4">
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="bi-info-circle me-2"></i>
            <div>
                {{ __('dashboard.welcome', ['name' => auth()->user()->name ?? auth()->user()->username]) }}
            </div>
        </div>

        <!-- Stats cards -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mt-2">
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('dashboard.stat_users') }}</div>
                            <div class="fs-4 fw-bold">{{ number_format($usersCount ?? 0) }}</div>
                        </div>
                        <i class="bi-people fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('dashboard.stat_items') }}</div>
                            <div class="fs-4 fw-bold">3,670</div>
                        </div>
                        <i class="bi-box fs-2 text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('dashboard.stat_orders') }}</div>
                            <div class="fs-4 fw-bold">284</div>
                        </div>
                        <i class="bi-bag fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ __('dashboard.stat_revenue') }}</div>
                            <div class="fs-4 fw-bold">$12,540</div>
                        </div>
                        <i class="bi-cash-coin fs-2 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overview and activities -->
        <div class="row g-3 mt-1">
            <div class="col-12 col-xl-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">{{ __('dashboard.overview') }}</h5>
                        </div>
                        <p class="text-muted mb-3">{{ __('dashboard.overview_desc') }}</p>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-muted">{{ __('dashboard.stat_users') }}</span>
                                        <span class="badge bg-primary">+5%</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-muted">{{ __('dashboard.stat_orders') }}</span>
                                        <span class="badge bg-success">+12%</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 48%;" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-muted">{{ __('dashboard.stat_items') }}</span>
                                        <span class="badge bg-warning text-dark">-2%</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-muted">{{ __('dashboard.stat_revenue') }}</span>
                                        <span class="badge bg-danger">+9%</span>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 55%;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">{{ __('dashboard.activities') }}</h5>
                            <a href="#" class="btn btn-sm btn-outline-secondary">{{ __('dashboard.view_all') }}</a>
                        </div>
                        <p class="text-muted mb-3">{{ __('dashboard.activities_desc') }}</p>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi-check-circle text-success me-2"></i>{{ __('dashboard.status_completed') }}</span>
                                <small class="text-muted">2h</small>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi-hourglass-split text-warning me-2"></i>{{ __('dashboard.status_pending') }}</span>
                                <small class="text-muted">5h</small>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi-x-circle text-danger me-2"></i>{{ __('dashboard.status_cancelled') }}</span>
                                <small class="text-muted">1d</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest items table -->
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">{{ __('dashboard.latest_items') }}</h5>
                    <a href="#" class="btn btn-sm btn-outline-secondary">{{ __('dashboard.view_all') }}</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('dashboard.th_item') }}</th>
                                <th>{{ __('dashboard.th_status') }}</th>
                                <th class="text-nowrap">{{ __('dashboard.th_updated') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>INV-001</td>
                                <td><span class="badge bg-success">{{ __('dashboard.status_completed') }}</span></td>
                                <td>2025-11-03 10:21</td>
                            </tr>
                            <tr>
                                <td>ORD-431</td>
                                <td><span class="badge bg-warning text-dark">{{ __('dashboard.status_pending') }}</span></td>
                                <td>2025-11-03 08:03</td>
                            </tr>
                            <tr>
                                <td>PRD-204</td>
                                <td><span class="badge bg-danger">{{ __('dashboard.status_cancelled') }}</span></td>
                                <td>2025-11-02 17:55</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
