<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.login_title') }}</title>
    <!-- Bootstrap CSS: RTL فقط عند العربية، وإلا LTR -->
    @if(app()->getLocale() === 'ar')
        <link href="{{ asset('css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    @endif
    <!-- Bootstrap Icons محلي عبر asset -->
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <!-- أنماط صفحة المصادقة -->
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <!-- زر تبديل اللغة عائم -->
    <div class="position-fixed top-0 start-0 p-3" style="z-index: 1050;">
        @php $nextLocale = app()->getLocale() === 'ar' ? 'en' : 'ar'; @endphp
        <a href="{{ LaravelLocalization::getLocalizedURL($nextLocale, null, [], true) }}" class="btn btn-light btn-sm rounded-circle shadow" title="{{ $nextLocale === 'ar' ? __('language.ar') : __('language.en') }}" aria-label="toggle-language">
            <i class="bi bi-translate"></i>
        </a>
    </div>
    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <main class="container py-4 w-100">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-js" crossorigin="anonymous"></script>
    
</body>
</html>
