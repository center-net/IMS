    <!-- زر تبديل اللغة عائم -->
    <div class="position-fixed top-0 start-0 p-3" style="z-index: 1050;">
        @php $nextLocale = app()->getLocale() === 'ar' ? 'en' : 'ar'; @endphp
        <a href="{{ LaravelLocalization::getLocalizedURL($nextLocale, null, [], true) }}" class="btn btn-light btn-sm rounded-circle shadow" title="{{ $nextLocale === 'ar' ? __('language.ar') : __('language.en') }}" aria-label="toggle-language">
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
                    @include('layouts.partials.topbar.general-settings-dropdown')
                    @include('layouts.partials.topbar.inventory-settings-dropdown')
                </ul>
                <div class="d-flex align-items-center gap-2">
                    @include('layouts.partials.topbar.user-menu')
                </div>
            </div>
        </div>
    </nav>
