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
        // ملاحظة: سيتم تضمين هذا المسار داخل مجموعة الإعدادات العامة لإضافة حماية إضافية

        // Employee profile view (protected by view-user-profiles)
        Route::get('/employees/{user}', function (\App\Models\User $user) {
            return view('users.show', compact('user'));
        })->name('users.show')
            ->middleware(['auth', 'can:view-user-profiles']);
        Route::group(['middleware' => ['auth', 'can:view-general-settings']], function () {
            // Roles & Permissions management (still requires its own permission)
            Route::view('/employee-tasks', 'roles.index')
                ->name('roles.index')
                ->middleware(['can:view-roles']);

            // Users management
            Route::view('/employees', 'users.index')
                ->name('users.index')
                ->middleware(['can:view-users']);

            // Countries management
            Route::view('/countries', 'countries.index')
                ->name('countries.index')
                ->middleware(['can:view-countries']);

            // Cities management
            Route::view('/cities', 'cities.index')
                ->name('cities.index')
                ->middleware(['can:view-cities']);

            // Palestine-only cities page
            Route::view('/cities/palestine', 'cities.palestine')
                ->name('cities.palestine')
                ->middleware(['can:view-cities']);

            // Villages management
            Route::view('/villages', 'villages.index')
                ->name('villages.index')
                ->middleware(['can:view-villages']);

            // Palestine-only villages page
            Route::view('/villages/palestine', 'villages.palestine')
                ->name('villages.palestine')
                ->middleware(['can:view-villages']);

            // Company settings
            Route::view('/settings/company', 'companies.index')
                ->name('companies.index')
                ->middleware(['can:view-companies']);

            // Fiscal Years settings
            Route::view('/settings/fiscal-years', 'fiscal-years.index')
                ->name('fiscal-years.index')
                ->middleware(['can:view-fiscal-years']);

            // Fiscal Months settings
            Route::view('/settings/fiscal-months', 'fiscal-months.index')
                ->name('fiscal-months.index')
                ->middleware(['can:view-fiscal-years']);

            // Treasuries (Vaults) management
            Route::view('/settings/treasuries', 'treasuries.index')
                ->name('treasuries.index')
                ->middleware(['can:view-treasuries']);

            // Offers management
            Route::view('/settings/offers', 'offers.index')
                ->name('offers.index')
                ->middleware(['can:view-offers']);

            // Suppliers management (Inventory Settings)
            Route::view('/settings/inventory/suppliers', 'suppliers.index')
                ->name('suppliers.index')
                ->middleware(['can:view-suppliers']);

            // Representatives management (Inventory Settings)
            Route::view('/settings/inventory/representatives', 'representatives.index')
                ->name('representatives.index')
                ->middleware(['can:view-representatives']);

            // Currencies management (Inventory Settings)
            Route::view('/settings/inventory/currencies', 'currencies.index')
                ->name('currencies.index')
                ->middleware(['can:view-currencies']);

            // Categories (Inventory Settings)
            Route::view('/settings/inventory/categories', 'categories.index')
                ->name('categories.index')
                ->middleware(['can:view-categories']);

            // System Logs
            Route::view('/logs', 'logs.index')
                ->name('logs.index')
                ->middleware(['can:view-logs']);
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
    }

);
