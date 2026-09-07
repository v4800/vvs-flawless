<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $watches = Watch::query()
            ->select([
                'id',
                'updated_at',
            ])
            ->orderBy('id')
            ->get();

        $staticPages = [
            [
                'fr' => 'watches.index',
                'nl' => 'nl.watches.index',
                'en' => 'en.watches.index',
            ],
            [
                'fr' => 'about',
                'nl' => 'nl.about',
                'en' => 'en.about',
            ],
            [
                'fr' => 'guides.diamond-vs-moissanite',
                'nl' => 'nl.guides.diamond-vs-moissanite',
                'en' => 'en.guides.diamond-vs-moissanite',
            ],
            [
                'fr' => 'guides.vvs-watch',
                'nl' => 'nl.guides.vvs-watch',
                'en' => 'en.guides.vvs-watch',
            ],
            [
                'fr' => 'guides.men-women',
                'nl' => 'nl.guides.men-women',
                'en' => 'en.guides.men-women',
            ],
            [
                'fr' => 'guides.belgium',
                'nl' => 'nl.guides.belgium',
                'en' => 'en.guides.belgium',
            ],
        ];

        return response()->view(
            'sitemap',
            [
                'watches' => $watches,
                'staticPages' => $staticPages,
            ],
            200,
            [
                'Content-Type' => 'application/xml; charset=UTF-8',
            ]
        );
    }

    public function robots(): Response
    {
        $content = implode(PHP_EOL, [
            'User-agent: *',
            'Allow: /',
            'Disallow: /dashboard',
            'Disallow: /settings',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /forgot-password',
            'Disallow: /reset-password',
            'Disallow: /reservation-confirmed/',
            'Disallow: /nl/reservation-confirmed/',
            'Disallow: /en/reservation-confirmed/',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response(
            $content,
            200,
            [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]
        );
    }
}
