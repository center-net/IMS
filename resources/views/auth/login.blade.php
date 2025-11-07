@extends('layouts.auth')

@section('content')
<div class="row min-vh-100 align-items-center justify-content-center">
    <!-- Hero / معلومات ترحيبية -->
    <!-- نخفي عمود الـ Hero ليصبح النموذج في المنتصف دائمًا -->
    <div class="d-none">
        <div class="pe-lg-5">
            <div class="mb-4">
                <i class="bi bi-shield-lock" style="font-size: 2.5rem"></i>
            </div>
            <h1 class="display-6 fw-bold mb-3">{{ __('auth.hero_title') }}</h1>
            <p class="lead text-white-75 mb-4">{{ __('auth.hero_lead') }}</p>
            <ul class="list-unstyled text-white-75 small">
                <li class="mb-2"><i class="bi bi-check2-circle me-2"></i> {{ __('auth.hero_feature_rtl') }}</li>
                <li class="mb-2"><i class="bi bi-check2-circle me-2"></i> {{ __('auth.hero_feature_remember') }}</li>
                <li class="mb-2"><i class="bi bi-check2-circle me-2"></i> {{ __('auth.hero_feature_protection') }}</li>
            </ul>
        </div>
    </div>

    <!-- بطاقة تسجيل الدخول -->
    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto">
        <div class="auth-card p-4 p-md-5 text-white shadow-lg">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock" style="font-size: 2.25rem"></i>
                <h1 class="h4 mt-2">{{ __('auth.login_title') }}</h1>
                <p class="mb-0 text-white-50">{{ __('auth.login_subtitle') }}</p>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('auth.identity_label') }}</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-transparent text-white-50"><i class="bi bi-person"></i></span>
                        <input type="text" name="identity" class="form-control form-control-lg" placeholder="{{ __('auth.identity_placeholder') }}" value="{{ old('identity') }}" required>
                    </div>
                    @error('identity')
                        <div class="text-warning small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('auth.password_label') }}</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-transparent text-white-50"><i class="bi bi-lock"></i></span>
                        <input type="password" id="loginPasswordInput" name="password" class="form-control form-control-lg" placeholder="{{ __('auth.password_placeholder') }}" required>
                        <button type="button" class="btn btn-outline-light" id="toggleLoginPasswordBtn" title="{{ __('auth.password_label') }}" aria-label="toggle-password-visibility">
                            <i class="bi-eye" id="toggleLoginPasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-warning small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">{{ __('auth.remember_me') }}</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-light btn-lg w-100 py-2">
                    <i class="bi bi-box-arrow-in-right"></i>
                    {{ __('auth.submit') }}
                </button>
            </form>
            <script>
                (function() {
                    function initToggle() {
                        var input = document.getElementById('loginPasswordInput');
                        var btn = document.getElementById('toggleLoginPasswordBtn');
                        var icon = document.getElementById('toggleLoginPasswordIcon');
                        if (!input || !btn || !icon) return;
                        btn.addEventListener('click', function() {
                            var isHidden = input.getAttribute('type') === 'password';
                            input.setAttribute('type', isHidden ? 'text' : 'password');
                            icon.classList.toggle('bi-eye', !isHidden);
                            icon.classList.toggle('bi-eye-slash', isHidden);
                        }, { once: false });
                    }
                    document.addEventListener('DOMContentLoaded', initToggle);
                })();
            </script>
        </div>
    </div>
    
    @endsection
