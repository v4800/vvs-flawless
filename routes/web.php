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
| FRENCH PUBLIC ROUTES
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

Route::get('/confidentialite', [PublicPageController::class, 'privacy'])
    ->name('privacy');

Route::get(
    '/guide/montre-diamant-ou-moissanite',
    [PublicPageController::class, 'diamondVsMoissanite']
)->name('guides.diamond-vs-moissanite');

Route::get(
    '/guide/montre-vvs-diamant-moissanite',
    [PublicPageController::class, 'vvsWatch']
)->name('guides.vvs-watch');

Route::get(
    '/guide/montre-diamant-moissanite-homme-femme',
    [PublicPageController::class, 'menWomen']
)->name('guides.men-women');

Route::get(
    '/belgique/montre-diamant-moissanite-vvs',
    [PublicPageController::class, 'belgiumWatchGuide']
)->name('guides.belgium');

Route::get(
    '/sitemap.xml',
    [SeoController::class, 'sitemap']
)->name('sitemap');

Route::get(
    '/robots.txt',
    [SeoController::class, 'robots']
)->name('robots');

Route::get('/watches', [WatchController::class, 'index'])
    ->name('watches.index');

Route::get('/watches/{watch}', [WatchController::class, 'show'])
    ->name('watches.show');

Route::post('/reservations', [ReservationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('reservations.store');

Route::get(
    '/reservation-confirmed/{reservationNumber}',
    [ReservationController::class, 'confirmation']
)
    ->middleware([
        'signed',
        'throttle:30,1',
    ])
    ->name('reservations.confirmation');

/*
|--------------------------------------------------------------------------
| DUTCH PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('nl')
    ->name('nl.')
    ->group(function () {
        Route::get(
            '/reservatievoorwaarden',
            [PublicPageController::class, 'reservationTerms']
        )->name('reservation-terms');

        Route::get('/privacy', [PublicPageController::class, 'privacy'])
            ->name('privacy');

        Route::get('/over-ons', [PublicPageController::class, 'about'])
            ->name('about');

        Route::get(
            '/gids/diamanten-horloge-of-moissanite',
            [PublicPageController::class, 'diamondVsMoissanite']
        )->name('guides.diamond-vs-moissanite');

        Route::get(
            '/gids/vvs-horloge-diamant-moissanite',
            [PublicPageController::class, 'vvsWatch']
        )->name('guides.vvs-watch');

        Route::get(
            '/gids/diamanten-moissanite-horloge-heren-dames',
            [PublicPageController::class, 'menWomen']
        )->name('guides.men-women');

        Route::get(
            '/belgie/diamanten-moissanite-vvs-horloge',
            [PublicPageController::class, 'belgiumWatchGuide']
        )->name('guides.belgium');

        Route::get(
            '/',
            function (Request $request) {
                return redirect()->route(
                    'nl.watches.index',
                    $request->query()
                );
            }
        )->name('home');

        Route::get('/watches', [WatchController::class, 'index'])
            ->name('watches.index');

        Route::get('/watches/{watch}', [WatchController::class, 'show'])
            ->name('watches.show');

        Route::post('/reservations', [ReservationController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('reservations.store');

        Route::get(
            '/reservation-confirmed/{reservationNumber}',
            [ReservationController::class, 'confirmation']
        )
            ->middleware([
                'signed',
                'throttle:30,1',
            ])
            ->name('reservations.confirmation');
    });

/*
|--------------------------------------------------------------------------
| ENGLISH PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('en')
    ->name('en.')
    ->group(function () {
        Route::get(
            '/reservation-terms',
            [PublicPageController::class, 'reservationTerms']
        )->name('reservation-terms');

        Route::get('/privacy', [PublicPageController::class, 'privacy'])
            ->name('privacy');

        Route::get('/about', [PublicPageController::class, 'about'])
            ->name('about');

        Route::get(
            '/guide/diamond-watch-or-moissanite',
            [PublicPageController::class, 'diamondVsMoissanite']
        )->name('guides.diamond-vs-moissanite');

        Route::get(
            '/guide/vvs-diamond-moissanite-watch',
            [PublicPageController::class, 'vvsWatch']
        )->name('guides.vvs-watch');

        Route::get(
            '/guide/diamond-moissanite-watches-men-women',
            [PublicPageController::class, 'menWomen']
        )->name('guides.men-women');

        Route::get(
            '/belgium/diamond-moissanite-vvs-watches',
            [PublicPageController::class, 'belgiumWatchGuide']
        )->name('guides.belgium');

        Route::get(
            '/',
            function (Request $request) {
                return redirect()->route(
                    'en.watches.index',
                    $request->query()
                );
            }
        )->name('home');

        Route::get('/watches', [WatchController::class, 'index'])
            ->name('watches.index');

        Route::get('/watches/{watch}', [WatchController::class, 'show'])
            ->name('watches.show');

        Route::post('/reservations', [ReservationController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('reservations.store');

        Route::get(
            '/reservation-confirmed/{reservationNumber}',
            [ReservationController::class, 'confirmation']
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
*/

Route::middleware([
    'auth',
    EnsureUserIsAdmin::class,
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::patch(
        '/dashboard/reservations/{reservation}/status',
        [DashboardController::class, 'updateStatus']
    )->name('dashboard.reservations.status');
});

require __DIR__.'/settings.php';
