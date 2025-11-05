<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'IMS')</title>
    @if(app()->getLocale() === 'ar')
        <link href="{{ asset('css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    @endif
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        .gradient-bg{ background: linear-gradient(135deg, #0d6efd 0%, #6610f2 50%, #6f42c1 100%); }
    </style>
    @livewireStyles
</head>
<body class="bg-light">
    <!-- زر تبديل اللغة عائم -->
    <div class="position-fixed top-0 start-0 p-3" style="z-index: 1050;">
        @php $nextLocale = app()->getLocale() === 'ar' ? 'en' : 'ar'; @endphp
        <a href="{{ LaravelLocalization::getLocalizedURL($nextLocale, null, [], true) }}" class="btn btn-light btn-sm rounded-circle shadow" title="{{ $nextLocale === 'ar' ? 'العربية' : 'English' }}" aria-label="toggle-language">
            <i class="bi bi-translate"></i>
        </a>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark gradient-bg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-brightness-alt-high"></i> IMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">{{ __('dashboard.title') }}</a></li>
                    <!-- General settings dropdown next to Dashboard -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="generalSettingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('menu.general_settings') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="generalSettingsDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi-gear me-1"></i> {{ __('menu.general_settings') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-calendar3 me-1"></i> {{ __('menu.fiscal_years') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-safe me-1"></i> {{ __('menu.warehouses') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-diagram-3 me-1"></i> {{ __('menu.manufacturing_lines') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-bar-chart-steps me-1"></i> {{ __('menu.employee_grades') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-people me-1"></i> {{ __('menu.employee_data') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-clipboard-check me-1"></i> {{ __('menu.attendance_log') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-award me-1"></i> {{ __('menu.rewards') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-dash-circle me-1"></i> {{ __('menu.discounts') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('cities.palestine') }}"><i class="bi-geo-alt me-1"></i> {{ __('menu.governorates') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('villages.palestine') }}"><i class="bi-pin-map me-1"></i> {{ __('menu.villages') }}</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="managementDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('menu.management') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="managementDropdown">
                            <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="bi-people me-1"></i> {{ __('menu.users') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('roles.index') }}"><i class="bi-shield-lock me-1"></i> {{ __('menu.roles') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('permissions.index') }}"><i class="bi-key me-1"></i> {{ __('menu.permissions') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">{{ __('menu.address') }}</h6></li>
                            <li><a class="dropdown-item" href="{{ route('countries.index') }}"><i class="bi-geo-alt me-1"></i> {{ __('menu.countries') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('cities.index') }}"><i class="bi-geo me-1"></i> {{ __('menu.cities') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('villages.index') }}"><i class="bi-pin-map me-1"></i> {{ __('menu.villages') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('logs.index') }}"><i class="bi-clipboard-data me-1"></i> {{ __('menu.logs') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-truck me-1"></i> {{ __('menu.suppliers') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-people-fill me-1"></i> {{ __('menu.customers') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">{{ __('menu.settings') }}</h6></li>
                            <li><a class="dropdown-item" href="{{ route('companies.index') }}"><i class="bi-building me-1"></i> {{ __('menu.company_settings') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi-receipt me-1"></i> {{ __('menu.tax_settings') }}</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi-person-circle"></i>
                                {{ auth()->user()->name ?? auth()->user()->username }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi-person"></i> {{ __('auth.user_menu_profile') }}
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi-box-arrow-right"></i> {{ __('auth.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">
                            <i class="bi-door-open"></i> {{ __('auth.login_title') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div id="alerts-container" class="position-fixed p-3" style="z-index: 1080; {{ app()->getLocale() === 'ar' ? 'top: 0; left: 0;' : 'top: 0; right: 0;' }}"></div>
    <main class="container py-4">
        @includeIf('partials.alerts')
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    <script>
        // Global toast notifications
        window.addEventListener('notify', function (event) {
            const detail = event.detail || {};
            const type = detail.type || 'info';
            const message = detail.message || '';
            const container = document.getElementById('alerts-container');
            if (!container || !message) return;

            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type} border-0 shadow`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            container.appendChild(toast);

            const delay = typeof detail.delay === 'number'
                ? detail.delay
                : (type === 'danger' || type === 'warning' ? 4000 : (type === 'success' ? 2500 : 3000));
            const t = new bootstrap.Toast(toast, { autohide: true, delay });
            t.show();
        });
    </script>
    @stack('scripts')
    </body>
    </html>
