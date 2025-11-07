<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/custom/livewire/update', $handle);
});
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {



        Route::get('/', function () {
            return redirect()->route('login');
        });

        Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
        Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('guest');

        Route::get('/dashboard', function () {
            $usersCount = User::query()->count();
            return view('dashboard', ['usersCount' => $usersCount]);
        })->name('dashboard')->middleware('auth');

        // Users management (protected by permissions)
        Route::view('/users', 'users.index')
            ->name('users.index')
            ->middleware(['auth', 'can:view-users']);

        // Roles & Permissions management (protected by permissions)
        Route::view('/employee-tasks', 'roles.index')
            ->name('roles.index')
            ->middleware(['auth', 'can:view-roles']);
        Route::view('/permissions', 'permissions.index')
            ->name('permissions.index')
            ->middleware(['auth', 'can:view-permissions']);

        // Countries management (protected by permissions)
        Route::view('/countries', 'countries.index')
            ->name('countries.index')
            ->middleware(['auth', 'can:view-countries']);

        // Cities management (protected by permissions)
        Route::view('/cities', 'cities.index')
            ->name('cities.index')
            ->middleware(['auth', 'can:view-cities']);

        // Palestine-only cities page (protected by permissions)
        Route::view('/cities/palestine', 'cities.palestine')
            ->name('cities.palestine')
            ->middleware(['auth', 'can:view-cities']);

        // Villages management (protected by permissions)
        Route::view('/villages', 'villages.index')
            ->name('villages.index')
            ->middleware(['auth', 'can:view-villages']);

        // Palestine-only villages page (protected by permissions)
        Route::view('/villages/palestine', 'villages.palestine')
            ->name('villages.palestine')
            ->middleware(['auth', 'can:view-villages']);

        // Company settings (protected by permissions)
        Route::view('/settings/company', 'companies.index')
            ->name('companies.index')
            ->middleware(['auth', 'can:view-companies']);

        // Fiscal Years settings (protected by permissions)
        Route::view('/settings/fiscal-years', 'fiscal-years.index')
            ->name('fiscal-years.index')
            ->middleware(['auth', 'can:view-fiscal-years']);

        // Fiscal Months settings (protected by permissions; reuse view-fiscal-years for now)
        Route::view('/settings/fiscal-months', 'fiscal-months.index')
            ->name('fiscal-months.index')
            ->middleware(['auth', 'can:view-fiscal-years']);

        // Treasuries (Vaults) management (protected by permissions)
        Route::view('/settings/treasuries', 'treasuries.index')
            ->name('treasuries.index')
            ->middleware(['auth', 'can:view-treasuries']);

        // Offers management (protected by permissions)
        Route::view('/settings/offers', 'offers.index')
            ->name('offers.index')
            ->middleware(['auth', 'can:view-offers']);

        // System Logs (protected by permissions)
        Route::view('/logs', 'logs.index')
            ->name('logs.index')
            ->middleware(['auth', 'can:view-logs']);

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
    }
);
