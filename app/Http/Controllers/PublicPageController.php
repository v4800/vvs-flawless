<?php

namespace App\Http\Controllers;

use Inertia\Response;

class PublicPageController extends Controller
{
    public function about(): Response
    {
        $routeName = $this->localizedRouteName('about');

        $seo = $this->seo(
            (string) trans('site.seo.about_title'),
            (string) trans('site.seo.about_description'),
            route($routeName),
            'about'
        );

        $seo['structuredData'] = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'VVS FLAWLESS',
            'url' => url('/'),
            'logo' => url('/images/vvs-flawless-profile.webp'),
            'sameAs' => [
                'https://www.instagram.com/vvsflawless43/',
                'https://www.tiktok.com/@vvsflawless43',
            ],
        ];

        return inertia('About', [
            'seo' => $seo,
        ]);
    }

    public function privacy(): Response
    {
        $routeName = $this->localizedRouteName('privacy');

        return inertia('Legal/Privacy', [
            'seo' => $this->seo(
                (string) trans('site.legal.privacy.seo_title'),
                (string) trans('site.legal.privacy.sections.0.text'),
                route($routeName),
                'privacy'
            ),
        ]);
    }

    public function reservationTerms(): Response
    {
        $routeName = $this->localizedRouteName('reservation-terms');

        return inertia('Legal/ReservationTerms', [
            'seo' => $this->seo(
                (string) trans('site.legal.terms.seo_title'),
                (string) trans('site.legal.terms.sections.0.paragraphs.0'),
                route($routeName),
                'reservation-terms'
            ),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function seo(
        string $title,
        string $description,
        string $canonical,
        string $page
    ): array {
        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'alternates' => [
                [
                    'hreflang' => 'fr-BE',
                    'href' => route($page),
                ],
                [
                    'hreflang' => 'nl-BE',
                    'href' => route('nl.'.$page),
                ],
                [
                    'hreflang' => 'x-default',
                    'href' => route($page),
                ],
            ],
            'locale' => app()->getLocale(),
            'image' => url('/images/vvs-flawless-profile.webp'),
            'type' => 'website',
        ];
    }

    private function localizedRouteName(string $name): string
    {
        return app()->getLocale() === 'nl_BE'
            ? 'nl.'.$name
            : $name;
    }
}
