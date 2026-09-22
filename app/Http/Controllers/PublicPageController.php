<?php

namespace App\Http\Controllers;

use App\Support\LocalizedRoute;
use Inertia\Response;

class PublicPageController extends Controller
{
    public function __construct(
        private readonly LocalizedRoute $localizedRoute
    ) {}

    public function about(): Response
    {
        $routeName = $this->localizedRoute->name('about');

        $seo = $this->seo(
            (string) trans('site.seo.about_title'),
            (string) trans('site.seo.about_description'),
            route($routeName),
            'about'
        );

        $seo['structuredData'] = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => url('/').'#organization',
            'name' => 'VVS FLAWLESS',
            'url' => url('/'),
            'logo' => url('/images/vvs-flawless-profile.webp'),
            'description' => (string) trans('site.seo.about_description'),
            'areaServed' => $this->serviceAreas(),
            'knowsAbout' => [
                'VVS moissanite watches',
                'Iced-out watches',
                'Fully set watches',
                'Diamond and moissanite comparison',
            ],
            'sameAs' => [
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
        $routeName = $this->localizedRoute->name($page);
        $guide = (array) trans('guides.diamond_vs_moissanite');

        return inertia('Guides/DiamondVsMoissanite', [
            'seo' => $this->articleSeo($guide, $page, $routeName),
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
            'guides.belgium',
            $this->belgiumAlternates('guides.belgium'),
            match (app()->getLocale()) {
                'nl_BE' => 'nl-BE',
                'en_BE' => 'en-BE',
                'de_BE' => 'de-BE',
                default => 'fr-BE',
            }
        );
    }

    public function franceWatchGuide(): Response
    {
        return $this->intentGuide(
            'france',
            'guides.france',
            [],
            'fr-FR'
        );
    }

    public function germanyWatchGuide(): Response
    {
        return $this->intentGuide(
            'germany',
            'guides.germany',
            [],
            'de-DE'
        );
    }

    public function netherlandsWatchGuide(): Response
    {
        return $this->intentGuide(
            'netherlands',
            'guides.netherlands',
            [],
            'nl-NL'
        );
    }

    public function privacy(): Response
    {
        $routeName = $this->localizedRoute->name('privacy');

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
        $routeName = $this->localizedRoute->name('reservation-terms');

        return inertia('Legal/ReservationTerms', [
            'seo' => $this->seo(
                (string) trans('site.legal.terms.seo_title'),
                (string) trans('site.legal.terms.sections.0.paragraphs.0'),
                route($routeName),
                'reservation-terms'
            ),
        ]);
    }

    private function intentGuide(
        string $translationKey,
        string $page,
        ?array $alternates = null,
        ?string $languageTag = null
    ): Response {
        $routeName = $this->localizedRoute->name($page);
        $guide = (array) trans('seo_intents.'.$translationKey);

        return inertia('Guides/SeoIntent', [
            'seo' => $this->articleSeo(
                $guide,
                $page,
                $routeName,
                $alternates,
                $languageTag
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
        string $routeName,
        ?array $alternates = null,
        ?string $languageTag = null
    ): array {
        $canonical = route($routeName);

        $seo = $this->seo(
            (string) ($guide['seo_title'] ?? 'VVS FLAWLESS'),
            (string) ($guide['seo_description'] ?? ''),
            $canonical,
            $page,
            $alternates
        );

        $graph = [
            [
                '@type' => 'Article',
                'headline' => (string) ($guide['title'] ?? ''),
                'description' => (string) ($guide['seo_description'] ?? ''),
                'mainEntityOfPage' => $canonical,
                'inLanguage' => $languageTag ?? $this->languageTag(),
                'about' => [
                    'Moissanite',
                    'VVS clarity',
                    'Iced-out watches',
                    'Diamond watches',
                ],
                'author' => [
                    '@type' => 'Organization',
                    'name' => 'VVS FLAWLESS',
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'VVS FLAWLESS',
                    'url' => url('/'),
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
                        'name' => trans('site.navigation.watches'),
                        'item' => route(
                            $this->localizedRoute->name('watches.index')
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
        ];

        $faqSchema = $this->faqSchema($guide['faq'] ?? null);

        if ($faqSchema !== null) {
            $graph[] = $faqSchema;
        }

        $seo['type'] = 'article';
        $seo['structuredData'] = [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
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
        string $page,
        ?array $alternates = null
    ): array {
        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'alternates' => $alternates ?? $this->alternates($page),
            'locale' => app()->getLocale(),
            'image' => url('/images/vvs-flawless-profile.webp'),
            'imageAlt' => 'VVS FLAWLESS',
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
                'hreflang' => 'fr',
                'href' => route($page),
            ],
            [
                'hreflang' => 'nl',
                'href' => route('nl.'.$page),
            ],
            [
                'hreflang' => 'en',
                'href' => route('en.'.$page),
            ],
            [
                'hreflang' => 'de',
                'href' => route('de.'.$page),
            ],
            [
                'hreflang' => 'x-default',
                'href' => route($page),
            ],
        ];
    }

    /**
     * @return list<array{hreflang: string, href: string}>
     */
    private function belgiumAlternates(string $page): array
    {
        return [
            ['hreflang' => 'fr-BE', 'href' => route($page)],
            ['hreflang' => 'nl-BE', 'href' => route('nl.'.$page)],
            ['hreflang' => 'en-BE', 'href' => route('en.'.$page)],
            ['hreflang' => 'de-BE', 'href' => route('de.'.$page)],
            ['hreflang' => 'x-default', 'href' => route($page)],
        ];
    }

    private function languageTag(): string
    {
        return match (app()->getLocale()) {
            'nl_BE' => 'nl',
            'en_BE' => 'en',
            'de_BE' => 'de',
            default => 'fr',
        };
    }

    /**
     * @return array<string, mixed>|null
     */
    private function faqSchema(mixed $faq): ?array
    {
        if (! is_array($faq)) {
            return null;
        }

        $entities = [];

        foreach ($faq as $item) {
            if (! is_array($item)) {
                continue;
            }

            $question = $item['question'] ?? null;
            $answer = $item['answer'] ?? null;

            if (! is_string($question)
                || $question === ''
                || ! is_string($answer)
                || $answer === '') {
                continue;
            }

            $entities[] = [
                '@type' => 'Question',
                'name' => $question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $answer,
                ],
            ];
        }

        if ($entities === []) {
            return null;
        }

        return [
            '@type' => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function serviceAreas(): array
    {
        return [
            ['@type' => 'Country', 'name' => 'Belgium'],
            ['@type' => 'Country', 'name' => 'France'],
            ['@type' => 'Country', 'name' => 'Germany'],
            ['@type' => 'Country', 'name' => 'Netherlands'],
        ];
    }
}
