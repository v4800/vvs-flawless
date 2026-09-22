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
    public function collection(
        Collection $watches,
        int $currentPage = 1,
        int $perPage = 12,
        bool $hasFilters = false
    ): array
    {
        $baseCollectionUrl = route(
            $this->localizedRoute->name('watches.index')
        );

        $collectionUrl = ! $hasFilters && $currentPage > 1
            ? $baseCollectionUrl.'?page='.$currentPage
            : $baseCollectionUrl;

        $alternates = $this->collectionAlternates();

        if (! $hasFilters && $currentPage > 1) {
            $alternates = array_map(
                static function (array $alternate) use ($currentPage): array {
                    $separator = str_contains($alternate['href'], '?')
                        ? '&'
                        : '?';

                    $alternate['href'] .= $separator.'page='.$currentPage;

                    return $alternate;
                },
                $alternates
            );
        }

        $positionOffset = max(
            0,
            ($currentPage - 1) * $perPage
        );

        $organizationId = url('/').'#organization';

        return [
            'title' => trans('seo_intents.collection_seo.title'),
            'description' => trans(
                'seo_intents.collection_seo.description'
            ),
            'canonical' => $collectionUrl,
            'robots' => $hasFilters ? 'noindex,follow' : 'index,follow',
            'alternates' => $alternates,
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
                                        'position' => $positionOffset + $index + 1,
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

        $organizationId = url('/').'#organization';
        $description = $this->productMetaDescription($watch);

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
                        'additionalProperty' => $this->productProperties($watch),
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
                    $this->organizationSchema($organizationId),
                ],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function productProperties(Watch $watch): array
    {
        if (PresentedWatch::matches($watch)) {
            $presentedCopy = $watch->getAttribute('presented_copy');

            if (! is_array($presentedCopy)
                || ! is_string($presentedCopy['stone_weight_label'] ?? null)
                || ! is_string($presentedCopy['stone_weight_value'] ?? null)) {
                throw new \LogicException(
                    'Missing localized presented watch schema copy.'
                );
            }

            return [
                [
                    '@type' => 'PropertyValue',
                    'name' => trans('site.product.stone'),
                    'value' => 'Moissanite',
                ],
                [
                    '@type' => 'PropertyValue',
                    'name' => $presentedCopy['stone_weight_label'],
                    'value' => $presentedCopy['stone_weight_value'],
                ],
            ];
        }

        $properties = [
            [
                '@type' => 'PropertyValue',
                'name' => trans('site.product.stone'),
                'value' => 'Moissanite VVS',
            ],
            [
                '@type' => 'PropertyValue',
                'name' => trans('site.product.color'),
                'value' => 'D',
            ],
            [
                '@type' => 'PropertyValue',
                'name' => trans('site.product.movement'),
                'value' => trans('site.movements.japonais')
                    .' / '
                    .trans('site.movements.suisse'),
            ],
        ];

        if (preg_match('/\b(\d{2})\s*mm\b/u', $watch->name, $matches) === 1) {
            $sizeLabel = match (app()->getLocale()) {
                'nl_BE' => 'Diameter',
                'en_BE' => 'Case diameter',
                'de_BE' => 'Gehäusedurchmesser',
                default => 'Diamètre',
            };

            $properties[] = [
                '@type' => 'PropertyValue',
                'name' => $sizeLabel,
                'value' => $matches[1].' mm',
            ];
        }

        return $properties;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function offersForWatch(Watch $watch): array
    {
        if (PresentedWatch::matches($watch)) {
            return [[
                '@type' => 'Offer',
                'name' => $watch->name,
                'url' => route($this->localizedRoute->name('watches.show'), $watch),
                'price' => (float) $watch->price,
                'priceCurrency' => 'EUR',
                'availability' => $this->structuredDataAvailability($watch),
                'seller' => [
                    '@id' => url('/').'#organization',
                ],
            ]];
        }

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
                    '@id' => url('/').'#organization',
                ],
            ];
        }

        return $offers;
    }

    private function productMetaDescription(Watch $watch): string
    {
        $description = trim((string) $watch->description);
        $shortDescription = trim((string) $watch->getAttribute(
            'short_description'
        ));
        $name = trim((string) $watch->name);

        $completeDescription = $this->fitCompleteSentences(
            $description,
            160
        );

        $candidates = array_values(array_unique(array_filter([
            $name !== '' && $shortDescription !== ''
                ? rtrim($name, '. ').'. '.rtrim($shortDescription, '. ').'.'
                : null,
            $description !== '' && Str::length($description) <= 160
                ? $description
                : null,
            $completeDescription !== ''
                ? $completeDescription
                : null,
            $shortDescription !== '' && Str::length($shortDescription) <= 160
                ? $shortDescription
                : null,
        ], static fn ($value): bool => is_string($value) && trim($value) !== '')));

        if ($candidates !== []) {
            usort(
                $candidates,
                static fn (string $a, string $b): int =>
                    abs(Str::length($a) - 150)
                    <=> abs(Str::length($b) - 150)
            );

            return $candidates[0];
        }

        $fallback = trim((string) trans(
            'seo_intents.collection_seo.description'
        ));

        return $this->fitCompleteSentences($fallback, 160) ?: $fallback;
    }

    private function fitCompleteSentences(string $text, int $limit): string
    {
        $text = trim($text);

        if ($text === '' || Str::length($text) <= $limit) {
            return $text;
        }

        $sentences = preg_split(
            '/(?<=[.!?])\s+/u',
            $text,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        if (! is_array($sentences)) {
            return '';
        }

        $result = '';

        foreach ($sentences as $sentence) {
            $candidate = $result === ''
                ? trim($sentence)
                : $result.' '.trim($sentence);

            if (Str::length($candidate) > $limit) {
                break;
            }

            $result = $candidate;
        }

        return $result;
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
            ['hreflang' => 'fr', 'href' => route('watches.index')],
            ['hreflang' => 'nl', 'href' => route('nl.watches.index')],
            ['hreflang' => 'en', 'href' => route('en.watches.index')],
            ['hreflang' => 'de', 'href' => route('de.watches.index')],
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
                'hreflang' => 'fr',
                'href' => route('watches.show', $watch),
            ],
            [
                'hreflang' => 'nl',
                'href' => route('nl.watches.show', $watch),
            ],
            [
                'hreflang' => 'en',
                'href' => route('en.watches.show', $watch),
            ],
            [
                'hreflang' => 'de',
                'href' => route('de.watches.show', $watch),
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
            'description' => trans('seo_intents.collection_seo.description'),
            'sameAs' => [
                'https://www.tiktok.com/@vvsflawless43',
            ],
            'knowsAbout' => [
                'Moissanite watches',
                'VVS moissanite',
                'Iced-out watches',
                'Hand-delivered watch reservations',
            ],
            'areaServed' => [
                [
                    '@type' => 'Country',
                    'name' => 'Belgium',
                ],
                [
                    '@type' => 'Country',
                    'name' => 'France',
                ],
                [
                    '@type' => 'Country',
                    'name' => 'Germany',
                ],
                [
                    '@type' => 'Country',
                    'name' => 'Netherlands',
                ],
            ],
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
}
