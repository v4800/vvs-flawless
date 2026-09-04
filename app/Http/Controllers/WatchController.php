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

        $watches = Watch::latest()->get();

        return inertia('Watches/Index', [
            'watches' => $watches,

            'seo' => [
                'title' => 'VVS FLAWLESS — Montres Iced-Out en Belgique',

                'description' => 'Découvrez la collection VVS FLAWLESS : montres iced-out en moissanite VVS couleur D, mouvements Japonais ou Suisse, disponibles sur réservation en Belgique.',

                'canonical' => route('watches.index'),

                'image' => url(
                    '/images/vvs-flawless-profile.webp'
                ),

                'type' => 'website',

                'structuredData' => [
                    '@context' => 'https://schema.org',

                    '@type' => 'WebSite',

                    'name' => 'VVS FLAWLESS',

                    'url' => url('/'),

                    'inLanguage' => 'fr-BE',

                    'publisher' => [
                        '@type' => 'Organization',

                        'name' => 'VVS FLAWLESS',

                        'url' => url('/'),

                        'logo' => url(
                            '/images/vvs-flawless-profile.webp'
                        ),

                        'sameAs' => [
                            'https://www.tiktok.com/@vvsflawless43',
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

        $selectedMovement =
            $request->query('movement') === 'Suisse'
                ? 'Suisse'
                : 'Japonais';

        $description = Str::limit(
            trim(
                $watch->name
                .'. '
                .$watch->description
                .' Moissanite VVS couleur D, mouvements Japonais ou Suisse, réservation avec remise en main propre ou livraison.'
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
            ->get();

        $watchUrl = route(
            'watches.show',
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

                    'category' => 'Montre iced-out en moissanite',

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
                            'name' => 'Montres',
                            'item' => route('watches.index'),
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
                    .' — VVS FLAWLESS',

                'description' => $description,

                'canonical' => $watchUrl,

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

                'name' => 'Mouvement '.$movement,

                'url' => route(
                    'watches.show',
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

    private function captureMarketingAttribution(
        Request $request
    ): void {
        $keys = [
            'utm_source',
            'utm_medium',
            'utm_campaign',
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

        if ($attribution !== []) {
            $request
                ->session()
                ->put(
                    'marketing_attribution',
                    $attribution
                );
        }
    }
}
