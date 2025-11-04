<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identity' => ['required','string','min:3'],
            'password' => ['required','string','min:4'],
            'remember' => ['sometimes','boolean'],
        ]);

        $key = 'login:'.Str::lower($request->input('identity')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withErrors(['identity' => __('تجاوزت عدد محاولات الدخول. انتظر :seconds ثانية.', ['seconds' => $seconds])])
                ->withInput($request->only('identity','remember'));
        }

        $identity = $request->input('identity');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $field = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $identity, 'password' => $password], $remember)) {
            RateLimiter::hit($key);
            return back()
                ->withErrors(['identity' => __('بيانات الدخول غير صحيحة')])
                ->withInput($request->only('identity','remember'));
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        $user = $request->user();
        if ($user) {
            $user->forceFill(['last_login_at' => now()])->save();
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

