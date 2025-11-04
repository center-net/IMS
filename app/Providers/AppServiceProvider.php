<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Models\SystemLog;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Livewire update endpoints (both non-localized and localized)
        Livewire::setUpdateRoute(function ($handle) {
            // Root endpoint (default used by client)
            $rootRoute = Route::post('/livewire/update', $handle)->middleware('web');

            // Localized endpoint to support prefixed locales
            Route::group([
                'prefix' => LaravelLocalization::setLocale(),
                'middleware' => ['web', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
            ], function () use ($handle) {
                Route::post('/livewire/update', $handle);
            });

            // Return the root route for Livewire to reference
            return $rootRoute;
        });

        // Register Livewire script endpoints (both non-localized and localized)
        Livewire::setScriptRoute(function ($handle) {
            // Root asset endpoint
            $rootRoute = Route::get('/livewire/livewire.js', $handle)->middleware('web');

            // Localized asset endpoint
            Route::group([
                'prefix' => LaravelLocalization::setLocale(),
                'middleware' => ['web', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
            ], function () use ($handle) {
                Route::get('/livewire/livewire.js', $handle);
            });

            return $rootRoute;
        });

        // Register authentication event listeners for auditing
        Event::listen(Login::class, function (Login $event) {
            try {
                SystemLog::create([
                    'user_id' => optional($event->user)->id,
                    'type' => 'auth',
                    'action' => 'login',
                    'ip' => request()->ip(),
                    'user_agent' => (string) request()->header('User-Agent'),
                    'context' => [
                        'guard' => $event->guard,
                    ],
                    'message' => __('logs.messages.auth_login'),
                    'locale' => app()->getLocale(),
                ]);
            } catch (\Throwable $e) {}
        });

        Event::listen(Logout::class, function (Logout $event) {
            try {
                SystemLog::create([
                    'user_id' => optional($event->user)->id,
                    'type' => 'auth',
                    'action' => 'logout',
                    'ip' => request()->ip(),
                    'user_agent' => (string) request()->header('User-Agent'),
                    'context' => [
                        'guard' => $event->guard,
                    ],
                    'message' => __('logs.messages.auth_logout'),
                    'locale' => app()->getLocale(),
                ]);
            } catch (\Throwable $e) {}
        });

        Event::listen(Failed::class, function (Failed $event) {
            try {
                SystemLog::create([
                    'user_id' => optional($event->user)->id,
                    'type' => 'auth',
                    'action' => 'login_failed',
                    'ip' => request()->ip(),
                    'user_agent' => (string) request()->header('User-Agent'),
                    'context' => [
                        'credentials' => collect($event->credentials)
                            ->except(['password'])
                            ->toArray(),
                        'guard' => $event->guard,
                    ],
                    'message' => __('logs.messages.auth_login_failed'),
                    'locale' => app()->getLocale(),
                ]);
            } catch (\Throwable $e) {}
        });
    }
}
