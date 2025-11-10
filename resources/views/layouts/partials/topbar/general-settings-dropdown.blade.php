@can('view-general-settings')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="generalSettingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('menu.general_settings') }}
    </a>
    <ul class="dropdown-menu" aria-labelledby="generalSettingsDropdown">
        @can('view-companies')
            <li><a class="dropdown-item" href="{{ route('companies.index') }}"><i class="bi-gear me-1"></i> {{ __('menu.company_settings') }}</a></li>
        @endcan
        @can('view-fiscal-years')
            <li><a class="dropdown-item" href="{{ route('fiscal-years.index') }}"><i class="bi-calendar3 me-1"></i> {{ __('menu.fiscal_years') }}</a></li>
        @endcan
        @can('view-fiscal-years')
            <li><a class="dropdown-item" href="{{ route('fiscal-months.index') }}"><i class="bi-calendar4-week me-1"></i> {{ __('menu.fiscal_months') }}</a></li>
        @endcan
        @can('view-treasuries')
            <li><a class="dropdown-item" href="{{ route('treasuries.index') }}"><i class="bi-safe me-1"></i> {{ __('menu.treasuries') }}</a></li>
        @endcan
        @can('view-offers')
            <li><a class="dropdown-item" href="{{ route('offers.index') }}"><i class="bi-tags me-1"></i> {{ __('menu.offers') }}</a></li>
        @endcan
        @can('view-warehouses')
            <li><a class="dropdown-item" href="#"><i class="bi-safe me-1"></i> {{ __('menu.warehouses') }}</a></li>
        @endcan
        @can('view-manufacturing-lines')
            <li><a class="dropdown-item" href="#"><i class="bi-diagram-3 me-1"></i> {{ __('menu.manufacturing_lines') }}</a></li>
        @endcan
        @can('view-roles')
            <li><a class="dropdown-item" href="{{ route('roles.index') }}"><i class="bi-bar-chart-steps me-1"></i> {{ __('menu.employee_grades') }}</a></li>
        @endcan
        @can('view-users')
            <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="bi-people me-1"></i> {{ __('menu.employee_data') }}</a></li>
        @endcan
        @can('view-attendance-log')
            <li><a class="dropdown-item" href="#"><i class="bi-clipboard-check me-1"></i> {{ __('menu.attendance_log') }}</a></li>
        @endcan
        @can('view-rewards')
            <li><a class="dropdown-item" href="#"><i class="bi-award me-1"></i> {{ __('menu.rewards') }}</a></li>
        @endcan
        @can('view-discounts')
            <li><a class="dropdown-item" href="#"><i class="bi-dash-circle me-1"></i> {{ __('menu.discounts') }}</a></li>
        @endcan
        @can('view-logs')
            <li><a class="dropdown-item" href="{{ route('logs.index') }}"><i class="bi-clipboard-data me-1"></i> {{ __('menu.logs') }}</a></li>
        @endcan
        @can('view-cities')
            <li><a class="dropdown-item" href="{{ route('cities.palestine') }}"><i class="bi-geo-alt me-1"></i> {{ __('menu.cities') }}</a></li>
        @endcan
        @can('view-villages')
            <li><a class="dropdown-item" href="{{ route('villages.palestine') }}"><i class="bi-pin-map me-1"></i> {{ __('menu.villages') }}</a></li>
        @endcan
    </ul>
</li>
@endcan
