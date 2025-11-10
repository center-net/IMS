<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="inventorySettingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('menu.inventory_settings') }}
    </a>
    <ul class="dropdown-menu" aria-labelledby="inventorySettingsDropdown">
        <li><a class="dropdown-item" href="{{ route('suppliers.index') }}"><i class="bi-truck me-1"></i> {{ __('menu.suppliers') }}</a></li>
        <li><a class="dropdown-item" href="{{ route('currencies.index') }}"><i class="bi-cash-coin me-1"></i> {{ __('menu.currencies') }}</a></li>
        <li><a class="dropdown-item" href="{{ route('representatives.index') }}"><i class="bi-person-badge me-1"></i> {{ __('menu.delegates') }}</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi-people me-1"></i> {{ __('menu.customers') }}</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi-building me-1"></i> {{ __('menu.warehouses') }}</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi-rulers me-1"></i> {{ __('menu.units') }}</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi-box-seam me-1"></i> {{ __('menu.items') }}</a></li>
    </ul>
</li>
