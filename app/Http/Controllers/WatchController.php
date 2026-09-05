<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Response;

class WatchController extends Controller
{
    public function index(Request $request): Response
    {
        $this->captureMarketingAttribution($request);

        $watches = Watch::latest()
            ->get()
            ->map(
                fn (Watch $watch) => $this->localizedWatch(
                    $watch
                )
            );

        $collectionUrl = route(
            $this->localizedRouteName('watches.index')
        );

        return inertia('Watches/Index', [
            'watches' => $watches,

            'seo' => [
                'title' => trans('seo_intents.collection_seo.title'),

                'description' => trans(
                    'seo_intents.collection_seo.description'
                ),

                'canonical' => $collectionUrl,

                'alternates' => $this->collectionAlternates(),

                'locale' => app()->getLocale(),

                'image' => url(
                    '/images/vvs-flawless-profile.webp'
                ),

                'type' => 'website',

                'structuredData' => [
                    '@context' => 'https://schema.org',

                    '@graph' => [
                        [
                            '@type' => 'WebSite',
                            'name' => 'VVS FLAWLESS',
                            'url' => $collectionUrl,
                            'inLanguage' => str_replace(
                                '_',
                                '-',
                                app()->getLocale()
                            ),
                            'publisher' => [
                                '@type' => 'Organization',
                                'name' => 'VVS FLAWLESS',
                                'url' => url('/'),
                                'logo' => url(
                                    '/images/vvs-flawless-profile.webp'
                                ),
                                'sameAs' => [
                                    'https://www.instagram.com/vvsflawless43/',
                                    'https://www.tiktok.com/@vvsflawless43',
                                ],
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
                            'inLanguage' => str_replace(
                                '_',
                                '-',
                                app()->getLocale()
                            ),
                            'mainEntity' => [
                                '@type' => 'ItemList',
                                'itemListElement' => $watches
                                    ->values()
                                    ->map(
                                        fn (Watch $watch, int $index) => [
                                            '@type' => 'ListItem',
                                            'position' => $index + 1,
                                            'name' => $watch->name,
                                            'url' => route(
                                                $this->localizedRouteName(
                                                    'watches.show'
                                                ),
                                                $watch
                                            ),
                                        ]
                                    )
                                    ->all(),
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function show(
        Request $request,
        Watch $watch
    ): Response {
        $this->captureMarketingAttribution($request);

        $watch = $this->localizedWatch($watch);

        $selectedMovement =
            $request->query('movement') === 'Suisse'
                ? 'Suisse'
                : 'Japonais';

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

        $relatedWatches = Watch::query()
            ->where('id', '!=', $watch->id)
            ->select([
                'id',
                'name',
                'image',
                'stock_quantity',
                'japanese_price',
                'japanese_promo_price',
                'swiss_price',
                'swiss_promo_price',
            ])
            ->latest('updated_at')
            ->limit(3)
            ->get()
            ->map(
                fn (Watch $relatedWatch) => $this->localizedWatch(
                    $relatedWatch
                )
            );

        $watchUrl = route(
            $this->localizedRouteName('watches.show'),
            $watch
        );

        $structuredData = [
            '@context' => 'https://schema.org',

            '@graph' => [
                [
                    '@type' => 'Product',

                    'name' => $watch->name,

                    'description' => $description,

                    'url' => $watchUrl,

                    'sku' => 'VVS-'.$watch->id,

                    'category' => trans('site.seo.product_category'),

                    'image' => [
                        $watch->image
                            ? url($watch->image)
                            : url(
                                '/images/vvs-flawless-profile.webp'
                            ),
                    ],

                    'offers' => $this->offersForWatch(
                        $watch
                    ),
                ],
                [
                    '@type' => 'BreadcrumbList',

                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => trans('site.navigation.watches'),
                            'item' => route(
                                $this->localizedRouteName(
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
        ];

        return inertia('Watches/Show', [
            'watch' => $watch,

            'selectedMovement' => $selectedMovement,

            'relatedWatches' => $relatedWatches,

            'seo' => [
                'title' => $watch->name
                    .' | Moissanite VVS | VVS FLAWLESS',

                'description' => $description,

                'canonical' => $watchUrl,

                'alternates' => $this->watchAlternates($watch),

                'locale' => app()->getLocale(),

                'image' => $watch->image
                    ? url($watch->image)
                    : url(
                        '/images/vvs-flawless-profile.webp'
                    ),

                'type' => 'product',

                'structuredData' => $structuredData,
            ],
        ]);
    }

    /**
     * @return list<array{
     *     '@type': string,
     *     name: string,
     *     url: string,
     *     price: float,
     *     priceCurrency: string,
     *     itemCondition: string,
     *     availability: string,
     *     seller: array{
     *         '@type': string,
     *         name: string,
     *         url: string
     *     }
     * }>
     */
    private function offersForWatch(
        Watch $watch
    ): array {
        $offers = [];

        $availability = $this->structuredDataAvailability(
            $watch
        );

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
                    $this->localizedRouteName('watches.show'),
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

    private function structuredDataAvailability(
        Watch $watch
    ): string {
        $availability = Str::lower(
            Str::ascii($watch->availability)
        );

        if (
            Str::contains(
                $availability,
                [
                    'sur commande',
                    'sur reservation',
                    'precommande',
                ]
            )
        ) {
            return 'https://schema.org/PreOrder';
        }

        if (
            ($watch->stock_quantity !== null
                && (int) $watch->stock_quantity <= 0)
            || Str::contains(
                $availability,
                [
                    'indisponible',
                    'rupture',
                    'epuise',
                ]
            )
        ) {
            return 'https://schema.org/OutOfStock';
        }

        return 'https://schema.org/InStock';
    }

    private function localizedRouteName(string $name): string
    {
        return match (app()->getLocale()) {
            'nl_BE' => 'nl.'.$name,
            'en_BE' => 'en.'.$name,
            default => $name,
        };
    }

    private function localizedWatch(Watch $watch): Watch
    {
        $translation = trans('watches.'.$watch->id);

        if (! is_array($translation)) {
            return $watch;
        }

        $localizedWatch = clone $watch;

        if (is_string($translation['name'] ?? null)) {
            $localizedWatch->name = $translation['name'];
        }

        if (is_string($translation['description'] ?? null)) {
            $localizedWatch->description = $translation['description'];
        }

        return $localizedWatch;
    }

    /**
     * @return list<array{hreflang: string, href: string}>
     */
    private function collectionAlternates(): array
    {
        return [
            [
                'hreflang' => 'fr-BE',
                'href' => route('watches.index'),
            ],
            [
                'hreflang' => 'nl-BE',
                'href' => route('nl.watches.index'),
            ],
            [
                'hreflang' => 'en-BE',
                'href' => route('en.watches.index'),
            ],
            [
                'hreflang' => 'x-default',
                'href' => route('watches.index'),
            ],
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

    private function captureMarketingAttribution(
        Request $request
    ): void {
        $keys = [
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
        ];

        $attribution = [];

        foreach ($keys as $key) {
            $value = $request->query($key);

            if (! is_string($value)) {
                continue;
            }

            $value = trim($value);

            if ($value === '') {
                continue;
            }

            $attribution[$key] = Str::limit(
                $value,
                100,
                ''
            );
        }

        if ($attribution === []) {
            return;
        }

        $referrer = $request->headers->get('referer');

        if (is_string($referrer) && trim($referrer) !== '') {
            $safeReferrer = $this->trackingUrl($referrer);

            if ($safeReferrer !== null) {
                $attribution['referrer'] = $safeReferrer;
            }
        }

        $attribution['landing_page'] = Str::limit(
            $request->url(),
            2048,
            ''
        );

        $request
            ->session()
            ->put(
                'marketing_attribution',
                $attribution
            );
    }

    private function trackingUrl(string $value): ?string
    {
        $parts = parse_url(trim($value));

        if (! is_array($parts)
            || ! in_array($parts['scheme'] ?? null, ['http', 'https'], true)
            || ! is_string($parts['host'] ?? null)) {
            return null;
        }

        $url = $parts['scheme'].'://'.$parts['host'];

        if (is_int($parts['port'] ?? null)) {
            $url .= ':'.$parts['port'];
        }

        if (is_string($parts['path'] ?? null)) {
            $url .= $parts['path'];
        }

        return Str::limit($url, 2048, '');
    }
}
