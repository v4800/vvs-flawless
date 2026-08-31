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

                'description' => 'Découvrez la collection VVS FLAWLESS : montres iced-out en moissanite VVS couleur D, mouvements Japonais ou Suisse et remise en main propre en Belgique.',

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
                .' Moissanite VVS couleur D, mouvements Japonais ou Suisse et remise en main propre en Belgique.'
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

        $structuredData = [
            '@context' => 'https://schema.org',

            '@type' => 'Product',

            'name' => $watch->name,

            'description' => $description,

            'url' => route(
                'watches.show',
                $watch
            ),

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
        ];

        return inertia('Watches/Show', [
            'watch' => $watch,

            'selectedMovement' => $selectedMovement,

            'relatedWatches' => $relatedWatches,

            'seo' => [
                'title' => $watch->name
                    .' — VVS FLAWLESS',

                'description' => $description,

                'canonical' => route(
                    'watches.show',
                    $watch
                ),

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

    private function offersForWatch(
        Watch $watch
    ): array {
        $offers = [];

        $availability =
            $watch->stock_quantity !== null
            && (int) $watch->stock_quantity <= 0
                ? 'https://schema.org/BackOrder'
                : 'https://schema.org/InStock';

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
