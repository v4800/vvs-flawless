<?php

namespace App\Support;

use App\Models\Watch;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class WatchSeo
{
    public function __construct(
        private readonly LocalizedRoute $localizedRoute
    ) {}

    /**
     * @param  Collection<int, Watch>  $watches
     * @return array<string, mixed>
     */
    public function collection(Collection $watches): array
    {
        $collectionUrl = route(
            $this->localizedRoute->name('watches.index')
        );

        $organizationId = url('/').'#organization';

        return [
            'title' => trans('seo_intents.collection_seo.title'),
            'description' => trans(
                'seo_intents.collection_seo.description'
            ),
            'canonical' => $collectionUrl,
            'alternates' => $this->collectionAlternates(),
            'locale' => app()->getLocale(),
            'image' => url('/images/vvs-flawless-profile.webp'),
            'imageAlt' => trans('seo_intents.collection_seo.image_alt'),
            'type' => 'website',
            'structuredData' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'WebSite',
                        'name' => 'VVS FLAWLESS',
                        'url' => $collectionUrl,
                        'inLanguage' => $this->languageTag(),
                        'publisher' => [
                            '@id' => $organizationId,
                        ],
                    ],
                    [
                        '@type' => 'CollectionPage',
                        'name' => trans(
                            'seo_intents.collection_seo.title'
                        ),
                        'description' => trans(
                            'seo_intents.collection_seo.description'
                        ),
                        'url' => $collectionUrl,
                        'inLanguage' => $this->languageTag(),
                        'isPartOf' => [
                            '@type' => 'WebSite',
                            'url' => $collectionUrl,
                        ],
                        'mainEntity' => [
                            '@type' => 'ItemList',
                            'numberOfItems' => $watches->count(),
                            'itemListElement' => $watches
                                ->values()
                                ->map(
                                    fn (Watch $watch, int $index) => [
                                        '@type' => 'ListItem',
                                        'position' => $index + 1,
                                        'name' => $watch->name,
                                        'url' => route(
                                            $this->localizedRoute->name(
                                                'watches.show'
                                            ),
                                            $watch
                                        ),
                                    ]
                                )
                                ->all(),
                        ],
                    ],
                    $this->organizationSchema($organizationId),
                ],
            ],
        ];
    }

    /**
     * @param  list<string>  $gallery
     * @return array<string, mixed>
     */
    public function product(Watch $watch, array $gallery): array
    {
        $watchUrl = route(
            $this->localizedRoute->name('watches.show'),
            $watch
        );

        $description = Str::limit(
            trim(
                $watch->name
                .'. '
                .$watch->description
                .' '
                .trans('seo_intents.collection_seo.product_suffix')
            ),
            160,
            '…'
        );

        $structuredImages = $gallery !== []
            ? array_map(
                fn (string $image): string => url($image),
                $gallery
            )
            : [
                $watch->image
                    ? url($watch->image)
                    : url('/images/vvs-flawless-profile.webp'),
            ];

        return [
            'title' => $watch->name
                .' | Moissanite VVS | VVS FLAWLESS',
            'description' => $description,
            'canonical' => $watchUrl,
            'alternates' => $this->watchAlternates($watch),
            'locale' => app()->getLocale(),
            'image' => $structuredImages[0],
            'imageAlt' => $watch->name.' — VVS FLAWLESS',
            'type' => 'product',
            'structuredData' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Product',
                        'name' => $watch->name,
                        'description' => $description,
                        'url' => $watchUrl,
                        'sku' => 'VVS-'.$watch->id,
                        'category' => trans('site.seo.product_category'),
                        'brand' => [
                            '@type' => 'Brand',
                            'name' => 'VVS FLAWLESS',
                        ],
                        'material' => trans('site.product.footer_material'),
                        'image' => $structuredImages,
                        'offers' => $this->offersForWatch($watch),
                    ],
                    [
                        '@type' => 'BreadcrumbList',
                        'itemListElement' => [
                            [
                                '@type' => 'ListItem',
                                'position' => 1,
                                'name' => trans('site.navigation.watches'),
                                'item' => route(
                                    $this->localizedRoute->name(
                                        'watches.index'
                                    )
                                ),
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 2,
                                'name' => $watch->name,
                                'item' => $watchUrl,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function offersForWatch(Watch $watch): array
    {
        $offers = [];
        $availability = $this->structuredDataAvailability($watch);

        $movements = [
            'Japonais' => $watch->japanese_promo_price
                ?? $watch->japanese_price,
            'Suisse' => $watch->swiss_promo_price
                ?? $watch->swiss_price,
        ];

        foreach ($movements as $movement => $price) {
            if (! is_numeric($price)) {
                continue;
            }

            $offers[] = [
                '@type' => 'Offer',
                'name' => trans(
                    'site.seo.offer_name',
                    [
                        'movement' => trans(
                            'site.movements.'.strtolower($movement)
                        ),
                    ]
                ),
                'url' => route(
                    $this->localizedRoute->name('watches.show'),
                    [
                        'watch' => $watch,
                        'movement' => $movement,
                    ]
                ),
                'price' => (float) $price,
                'priceCurrency' => 'EUR',
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => $availability,
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'VVS FLAWLESS',
                    'url' => url('/'),
                ],
            ];
        }

        return $offers;
    }

    private function structuredDataAvailability(Watch $watch): string
    {
        $availability = Str::lower(
            Str::ascii($watch->availability)
        );

        if (Str::contains(
            $availability,
            ['sur commande', 'sur reservation', 'precommande']
        )) {
            return 'https://schema.org/PreOrder';
        }

        if (
            ($watch->stock_quantity !== null
                && (int) $watch->stock_quantity <= 0)
            || Str::contains(
                $availability,
                ['indisponible', 'rupture', 'epuise']
            )
        ) {
            return 'https://schema.org/OutOfStock';
        }

        return 'https://schema.org/InStock';
    }

    /**
     * @return list<array{hreflang: string, href: string}>
     */
    private function collectionAlternates(): array
    {
        return [
            ['hreflang' => 'fr-BE', 'href' => route('watches.index')],
            ['hreflang' => 'nl-BE', 'href' => route('nl.watches.index')],
            ['hreflang' => 'en-BE', 'href' => route('en.watches.index')],
            ['hreflang' => 'x-default', 'href' => route('watches.index')],
        ];
    }

    /**
     * @return list<array{hreflang: string, href: string}>
     */
    private function watchAlternates(Watch $watch): array
    {
        return [
            [
                'hreflang' => 'fr-BE',
                'href' => route('watches.show', $watch),
            ],
            [
                'hreflang' => 'nl-BE',
                'href' => route('nl.watches.show', $watch),
            ],
            [
                'hreflang' => 'en-BE',
                'href' => route('en.watches.show', $watch),
            ],
            [
                'hreflang' => 'x-default',
                'href' => route('watches.show', $watch),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function organizationSchema(string $organizationId): array
    {
        return [
            '@type' => 'Organization',
            '@id' => $organizationId,
            'name' => 'VVS FLAWLESS',
            'url' => url('/'),
            'logo' => url('/images/vvs-flawless-profile.webp'),
            'sameAs' => [
                'https://www.instagram.com/vvsflawless43/',
                'https://www.tiktok.com/@vvsflawless43',
            ],
            'areaServed' => [
                [
                    '@type' => 'Country',
                    'name' => 'Belgium',
                ],
                [
                    '@type' => 'City',
                    'name' => 'Liège',
                    'containedInPlace' => [
                        '@type' => 'Country',
                        'name' => 'Belgium',
                    ],
                ],
                [
                    '@type' => 'AdministrativeArea',
                    'name' => 'Northern France',
                ],
                [
                    '@type' => 'City',
                    'name' => 'Maastricht',
                    'containedInPlace' => [
                        '@type' => 'Country',
                        'name' => 'Netherlands',
                    ],
                ],
                [
                    '@type' => 'City',
                    'name' => 'Gulpen',
                    'containedInPlace' => [
                        '@type' => 'Country',
                        'name' => 'Netherlands',
                    ],
                ],
            ],
        ];
    }

    private function languageTag(): string
    {
        return str_replace('_', '-', app()->getLocale());
    }
}
