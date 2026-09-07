<?php

namespace App\Http\Controllers;

use App\Support\LocalizedCopy;
use Inertia\Response;

class PublicPageController extends Controller
{
    public function about(): Response
    {
        $routeName = $this->localizedRouteName('about');

        $seo = $this->seo(
            LocalizedCopy::string('site.seo.about_title'),
            LocalizedCopy::string('site.seo.about_description'),
            route($routeName),
            'about'
        );

        $seo['structuredData'] = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'VVS FLAWLESS',
            'url' => url('/'),
            'logo' => url('/images/vvs-flawless-profile.webp'),
            'description' => LocalizedCopy::string(
                'site.seo.about_description'
            ),
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Belgium',
            ],
            'sameAs' => [
                'https://www.instagram.com/vvsflawless43/',
                'https://www.tiktok.com/@vvsflawless43',
            ],
        ];

        return inertia('About', [
            'seo' => $seo,
        ]);
    }

    public function diamondVsMoissanite(): Response
    {
        $page = 'guides.diamond-vs-moissanite';
        $routeName = $this->localizedRouteName($page);
        $guide = LocalizedCopy::array('guides.diamond_vs_moissanite');

        $seo = $this->articleSeo(
            $guide,
            $page,
            $routeName
        );

        return inertia('Guides/DiamondVsMoissanite', [
            'seo' => $seo,
            'guide' => $guide,
        ]);
    }

    public function vvsWatch(): Response
    {
        return $this->intentGuide(
            'vvs_watch',
            'guides.vvs-watch'
        );
    }

    public function menWomen(): Response
    {
        return $this->intentGuide(
            'men_women',
            'guides.men-women'
        );
    }

    public function belgiumWatchGuide(): Response
    {
        return $this->intentGuide(
            'belgium',
            'guides.belgium'
        );
    }

    public function privacy(): Response
    {
        $routeName = $this->localizedRouteName('privacy');
        $privacy = LocalizedCopy::array('site.legal.privacy');
        $sections = is_array($privacy['sections'] ?? null)
            ? $privacy['sections']
            : [];
        $firstSection = is_array($sections[0] ?? null)
            ? $sections[0]
            : [];

        return inertia('Legal/Privacy', [
            'seo' => $this->seo(
                (string) ($privacy['seo_title'] ?? 'VVS FLAWLESS'),
                (string) ($firstSection['text'] ?? ''),
                route($routeName),
                'privacy'
            ),
        ]);
    }

    public function reservationTerms(): Response
    {
        $routeName = $this->localizedRouteName('reservation-terms');
        $terms = LocalizedCopy::array('site.legal.terms');
        $sections = is_array($terms['sections'] ?? null)
            ? $terms['sections']
            : [];
        $firstSection = is_array($sections[0] ?? null)
            ? $sections[0]
            : [];
        $paragraphs = is_array($firstSection['paragraphs'] ?? null)
            ? $firstSection['paragraphs']
            : [];

        return inertia('Legal/ReservationTerms', [
            'seo' => $this->seo(
                (string) ($terms['seo_title'] ?? 'VVS FLAWLESS'),
                (string) ($paragraphs[0] ?? ''),
                route($routeName),
                'reservation-terms'
            ),
        ]);
    }

    private function intentGuide(
        string $translationKey,
        string $page
    ): Response {
        $routeName = $this->localizedRouteName($page);
        $guide = LocalizedCopy::array(
            'seo_intents.'.$translationKey
        );

        return inertia('Guides/SeoIntent', [
            'seo' => $this->articleSeo(
                $guide,
                $page,
                $routeName
            ),
            'guide' => $guide,
        ]);
    }

    /**
     * @param  array<string, mixed>  $guide
     * @return array<string, mixed>
     */
    private function articleSeo(
        array $guide,
        string $page,
        string $routeName
    ): array {
        $canonical = route($routeName);

        $seo = $this->seo(
            (string) ($guide['seo_title'] ?? 'VVS FLAWLESS'),
            (string) ($guide['seo_description'] ?? ''),
            $canonical,
            $page
        );

        $seo['type'] = 'article';
        $seo['structuredData'] = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Article',
                    'headline' => (string) ($guide['title'] ?? ''),
                    'description' => (string) ($guide['seo_description'] ?? ''),
                    'mainEntityOfPage' => $canonical,
                    'inLanguage' => str_replace(
                        '_',
                        '-',
                        app()->getLocale()
                    ),
                    'author' => [
                        '@type' => 'Organization',
                        'name' => 'VVS FLAWLESS',
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => 'VVS FLAWLESS',
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => url('/images/vvs-flawless-profile.webp'),
                        ],
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => LocalizedCopy::string(
                                'site.navigation.watches'
                            ),
                            'item' => route(
                                $this->localizedRouteName('watches.index')
                            ),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => (string) ($guide['title'] ?? ''),
                            'item' => $canonical,
                        ],
                    ],
                ],
            ],
        ];

        return $seo;
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
            'alternates' => $this->alternates($page),
            'locale' => app()->getLocale(),
            'image' => url('/images/vvs-flawless-profile.webp'),
            'type' => 'website',
        ];
    }

    /**
     * @return list<array{hreflang: string, href: string}>
     */
    private function alternates(string $page): array
    {
        return [
            [
                'hreflang' => 'fr-BE',
                'href' => route($page),
            ],
            [
                'hreflang' => 'nl-BE',
                'href' => route('nl.'.$page),
            ],
            [
                'hreflang' => 'en-BE',
                'href' => route('en.'.$page),
            ],
            [
                'hreflang' => 'x-default',
                'href' => route($page),
            ],
        ];
    }

    private function localizedRouteName(string $name): string
    {
        return match (app()->getLocale()) {
            'nl_BE' => 'nl.'.$name,
            'en_BE' => 'en.'.$name,
            default => $name,
        };
    }
}
