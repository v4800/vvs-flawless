<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\WatchController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/conditions-reservation', [PublicPageController::class, 'reservationTerms'])
    ->name('reservation-terms');

Route::get('/a-propos', [PublicPageController::class, 'about'])
    ->name('about');

Route::get(
    '/',
    function (Request $request) {
        return redirect()->route(
            'watches.index',
            $request->query()
        );
    }
)->name('home');

/*
|--------------------------------------------------------------------------
| SEO
|--------------------------------------------------------------------------
*/

Route::get('/confidentialite', [PublicPageController::class, 'privacy'])
    ->name('privacy');

Route::get(
    '/sitemap.xml',
    [
        SeoController::class,
        'sitemap',
    ]
)->name('sitemap');

Route::get(
    '/robots.txt',
    [
        SeoController::class,
        'robots',
    ]
)->name('robots');

/*
|--------------------------------------------------------------------------
| COLLECTION
|--------------------------------------------------------------------------
*/

Route::get(
    '/watches',
    [
        WatchController::class,
        'index',
    ]
)->name('watches.index');

/*
|--------------------------------------------------------------------------
| FICHE MONTRE
|--------------------------------------------------------------------------
*/

Route::get(
    '/watches/{watch}',
    [
        WatchController::class,
        'show',
    ]
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
    [
        ReservationController::class,
        'store',
    ]
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
| NEDERLANDSTALIGE OPENBARE ROUTES
|--------------------------------------------------------------------------
|
| De Franse URL's blijven ongewijzigd. Zo behouden bestaande links hun
| waarde en krijgt de Nederlandse versie eigen, indexeerbare URL's.
|
*/

Route::prefix('nl')
    ->name('nl.')
    ->group(function () {
        Route::get(
            '/reservatievoorwaarden',
            [PublicPageController::class, 'reservationTerms']
        )->name('reservation-terms');

        Route::get(
            '/privacy',
            [PublicPageController::class, 'privacy']
        )->name('privacy');

        Route::get(
            '/over-ons',
            [PublicPageController::class, 'about']
        )->name('about');

        Route::get(
            '/',
            function (Request $request) {
                return redirect()->route(
                    'nl.watches.index',
                    $request->query()
                );
            }
        )->name('home');

        Route::get(
            '/watches',
            [
                WatchController::class,
                'index',
            ]
        )->name('watches.index');

        Route::get(
            '/watches/{watch}',
            [
                WatchController::class,
                'show',
            ]
        )->name('watches.show');

        Route::post(
            '/reservations',
            [
                ReservationController::class,
                'store',
            ]
        )
            ->middleware('throttle:5,1')
            ->name('reservations.store');

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
            ->name('reservations.confirmation');
    });

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
