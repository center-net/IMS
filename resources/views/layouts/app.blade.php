<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('layouts.partials.head')
</head>
<body class="bg-light">
    @include('layouts.partials.topbar')

    <div id="alerts-container" class="position-fixed p-3" style="z-index: 1080; {{ app()->getLocale() === 'ar' ? 'top: 0; left: 0;' : 'top: 0; right: 0;' }}"></div>
    <main class="container py-4">
        @includeIf('partials.alerts')
        @yield('content')
    </main>

    @include('layouts.partials.scripts')
    </body>
    </html>
