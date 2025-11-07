<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="managementDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('menu.management') }}
    </a>
    <ul class="dropdown-menu" aria-labelledby="managementDropdown">
        @can('view-users')
            <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="bi-people me-1"></i> {{ __('menu.users') }}</a></li>
        @endcan
        @can('view-roles')
            <li><a class="dropdown-item" href="{{ route('roles.index') }}"><i class="bi-shield-lock me-1"></i> {{ __('menu.roles') }}</a></li>
        @endcan
        {{-- Permissions menu removed: management via seeders/commands only --}}
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
