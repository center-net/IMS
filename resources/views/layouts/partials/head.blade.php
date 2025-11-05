    @php
        $company = \App\Models\Company::query()->orderBy('id')->first();
        $companyName = optional($company?->translate(app()->getLocale()))->name ?? ($company->name ?? config('app.name', 'IMS'));
        $companyLogo = $company?->logo;
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $companyName)</title>
    @if(app()->getLocale() === 'ar')
        <link href="{{ asset('css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    @endif
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    @if($companyLogo)
        <link rel="icon" type="image/png" href="{{ asset($companyLogo) }}">
        <link rel="shortcut icon" href="{{ asset($companyLogo) }}">
        <link rel="apple-touch-icon" href="{{ asset($companyLogo) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
    @endif
    <style>
        .gradient-bg{ background: linear-gradient(135deg, #0d6efd 0%, #6610f2 50%, #6f42c1 100%); }
    </style>
    @livewireStyles
