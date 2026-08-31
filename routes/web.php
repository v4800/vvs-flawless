<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\WatchController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::redirect(
    '/',
    '/watches'
)->name('home');

/*
|--------------------------------------------------------------------------
| COLLECTION
|--------------------------------------------------------------------------
*/

Route::get(
    '/watches',
    [WatchController::class, 'index']
)->name('watches.index');

/*
|--------------------------------------------------------------------------
| FICHE MONTRE
|--------------------------------------------------------------------------
*/

Route::get(
    '/watches/{watch}',
    [WatchController::class, 'show']
)->name('watches.show');

/*
|--------------------------------------------------------------------------
| RÉSERVATION
|--------------------------------------------------------------------------
|
| Maximum 5 requêtes par minute.
|
*/

Route::post(
    '/reservations',
    [ReservationController::class, 'store']
)
    ->middleware(
        'throttle:5,1'
    )
    ->name(
        'reservations.store'
    );

/*
|--------------------------------------------------------------------------
| CONFIRMATION
|--------------------------------------------------------------------------
|
| URL signée obligatoire.
| Une personne ne peut donc pas simplement inventer un numéro VVS.
|
*/

Route::get(
    '/reservation-confirmed/{reservationNumber}',
    [
        ReservationController::class,
        'confirmation',
    ]
)
    ->middleware([
        'signed',
        'throttle:30,1',
    ])
    ->name(
        'reservations.confirmation'
    );

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
|
| Double protection :
|
| 1. auth
| 2. is_admin
|
*/

Route::middleware([
    'auth',
    EnsureUserIsAdmin::class,
])->group(function () {

    Route::get(
        '/dashboard',
        [
            DashboardController::class,
            'index',
        ]
    )->name(
        'dashboard'
    );

    Route::patch(
        '/dashboard/reservations/{reservation}/status',
        [
            DashboardController::class,
            'updateStatus',
        ]
    )->name(
        'dashboard.reservations.status'
    );
});

/*
|--------------------------------------------------------------------------
| SETTINGS
|--------------------------------------------------------------------------
*/

require __DIR__.'/settings.php';
