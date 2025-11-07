<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="generalSettingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('menu.general_settings') }}
    </a>
    <ul class="dropdown-menu" aria-labelledby="generalSettingsDropdown">
        <li><a class="dropdown-item" href="{{ route('companies.index') }}"><i class="bi-gear me-1"></i> {{ __('menu.company_settings') }}</a></li>
        <li><a class="dropdown-item" href="{{ route('fiscal-years.index') }}"><i class="bi-calendar3 me-1"></i> {{ __('menu.fiscal_years') }}</a></li>
        <li><a class="dropdown-item" href="{{ route('fiscal-months.index') }}"><i class="bi-calendar4-week me-1"></i> {{ __('menu.fiscal_months') }}</a></li>
        <li><a class="dropdown-item" href="{{ route('treasuries.index') }}"><i class="bi-safe me-1"></i> {{ __('menu.treasuries') }}</a></li>
        <li><a class="dropdown-item" href="{{ route('offers.index') }}"><i class="bi-tags me-1"></i> {{ __('menu.offers') }}</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi-safe me-1"></i> {{ __('menu.warehouses') }}</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi-diagram-3 me-1"></i> {{ __('menu.manufacturing_lines') }}</a></li>
        <li><a class="dropdown-item" href="{{ route('roles.index') }}"><i class="bi-bar-chart-steps me-1"></i> {{ __('menu.employee_grades') }}</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi-people me-1"></i> {{ __('menu.employee_data') }}</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi-clipboard-check me-1"></i> {{ __('menu.attendance_log') }}</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi-award me-1"></i> {{ __('menu.rewards') }}</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi-dash-circle me-1"></i> {{ __('menu.discounts') }}</a></li>
        <li><a class="dropdown-item" href="{{ route('cities.palestine') }}"><i class="bi-geo-alt me-1"></i> {{ __('menu.governorates') }}</a></li>
        <li><a class="dropdown-item" href="{{ route('villages.palestine') }}"><i class="bi-pin-map me-1"></i> {{ __('menu.villages') }}</a></li>
    </ul>
</li>

