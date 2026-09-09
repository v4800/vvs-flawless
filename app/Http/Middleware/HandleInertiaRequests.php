<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $routePrefix = match (app()->getLocale()) {
            'nl_BE' => 'nl.',
            'en_BE' => 'en.',
            default => '',
        };

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'locale' => app()->getLocale(),
            'translations' => fn () => trans('site'),
            'guideLinks' => fn () => trans('guides.links'),
            'seoIntentContent' => fn () => trans('seo_intents'),
            'localizedRoutes' => [
                'watches' => route(
                    $routePrefix.'watches.index',
                    absolute: false
                ),
                'reservationStore' => route(
                    $routePrefix.'reservations.store',
                    absolute: false
                ),
                'privacy' => route(
                    $routePrefix.'privacy',
                    absolute: false
                ),
                'reservationTerms' => route(
                    $routePrefix.'reservation-terms',
                    absolute: false
                ),
                'about' => route(
                    $routePrefix.'about',
                    absolute: false
                ),
                'diamondGuide' => route(
                    $routePrefix.'guides.diamond-vs-moissanite',
                    absolute: false
                ),
                'vvsGuide' => route(
                    $routePrefix.'guides.vvs-watch',
                    absolute: false
                ),
                'menWomenGuide' => route(
                    $routePrefix.'guides.men-women',
                    absolute: false
                ),
                'belgiumGuide' => route(
                    $routePrefix.'guides.belgium',
                    absolute: false
                ),
            ],
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
