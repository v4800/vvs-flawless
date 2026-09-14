<?php

use App\Http\Controllers\Admin\ReservationDashboardController;
use App\Http\Middleware\AdminSecurityHeaders;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    EnsureUserIsAdmin::class,
    AdminSecurityHeaders::class,
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.reservations.index');
    })->name('dashboard');

    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get(
                '/reservations',
                [ReservationDashboardController::class, 'index']
            )->name('reservations.index');

            Route::patch(
                '/reservations/{reservation}',
                [ReservationDashboardController::class, 'update']
            )->name('reservations.update');

            Route::patch(
                '/reservations/{reservation}/archive',
                [ReservationDashboardController::class, 'archive']
            )->name('reservations.archive');
        });

    // Compatibility for old dashboard forms and existing automated tests.
    Route::patch(
        '/dashboard/reservations/{reservation}/status',
        [ReservationDashboardController::class, 'update']
    )->name('dashboard.reservations.status');
});
