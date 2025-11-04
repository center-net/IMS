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
        Route::view('/roles', 'roles.index')
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

        // Villages management (protected by permissions)
        Route::view('/villages', 'villages.index')
            ->name('villages.index')
            ->middleware(['auth', 'can:view-villages']);

        // System Logs (protected by permissions)
        Route::view('/logs', 'logs.index')
            ->name('logs.index')
            ->middleware(['auth', 'can:view-logs']);

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
    }
);
