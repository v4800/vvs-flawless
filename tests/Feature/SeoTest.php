<?php

namespace Tests\Feature;

use App\Models\Watch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_is_public_and_contains_all_localized_public_content(): void
    {
        $watch = Watch::query()->create([
            'name' => 'Montre test SEO',
            'price' => 950,
            'description' => 'Description de test.',
            'availability' => 'Sur commande',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();

        $response->assertHeader(
            'Content-Type',
            'application/xml; charset=UTF-8'
        );

        foreach ([
            route('watches.index'),
            route('nl.watches.index'),
            route('en.watches.index'),
            route('de.watches.index'),
            route('watches.show', $watch),
            route('nl.watches.show', $watch),
            route('en.watches.show', $watch),
            route('de.watches.show', $watch),
            route('about'),
            route('nl.about'),
            route('en.about'),
            route('de.about'),
            route('guides.diamond-vs-moissanite'),
            route('nl.guides.diamond-vs-moissanite'),
            route('en.guides.diamond-vs-moissanite'),
            route('de.guides.diamond-vs-moissanite'),
            route('guides.vvs-watch'),
            route('nl.guides.vvs-watch'),
            route('en.guides.vvs-watch'),
            route('de.guides.vvs-watch'),
            route('guides.men-women'),
            route('nl.guides.men-women'),
            route('en.guides.men-women'),
            route('de.guides.men-women'),
            route('guides.belgium'),
            route('nl.guides.belgium'),
            route('en.guides.belgium'),
            route('de.guides.belgium'),
            route('guides.france'),
            route('de.guides.germany'),
            route('nl.guides.netherlands'),
        ] as $url) {
            $response->assertSee($url, false);
        }

        foreach (['fr', 'nl', 'en', 'de', 'fr-BE', 'nl-BE', 'en-BE', 'de-BE', 'x-default'] as $hreflang) {
            $response->assertSee(
                'hreflang="'.$hreflang.'"',
                false
            );
        }

        $response->assertSee(
            $watch->updated_at->toAtomString(),
            false
        );

        $response->assertDontSee(
            '/dashboard',
            false
        );
    }

    public function test_made_to_order_watch_uses_product_offer_and_breadcrumb_structured_data(): void
    {
        $watch = Watch::query()->create([
            'name' => 'Montre test sur commande',
            'price' => 950,
            'description' => 'Description de test.',
            'availability' => 'Sur commande',
            'stock_quantity' => null,
            'japanese_price' => 950,
            'swiss_price' => 1250,
        ]);

        $response = $this->get(
            route('watches.show', $watch)
        );

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                ->where(
                    'seo.structuredData.@graph.0.@type',
                    'Product'
                )
                ->where(
                    'seo.structuredData.@graph.0.offers.0.@type',
                    'Offer'
                )
                ->where(
                    'seo.structuredData.@graph.0.offers.0.priceCurrency',
                    'EUR'
                )
                ->where(
                    'seo.structuredData.@graph.0.offers.0.availability',
                    'https://schema.org/PreOrder'
                )
                ->where(
                    'seo.structuredData.@graph.0.offers.1.availability',
                    'https://schema.org/PreOrder'
                )
                ->where(
                    'seo.structuredData.@graph.0.offers.0.seller.@id',
                    url('/').'#organization'
                )
                ->where(
                    'seo.structuredData.@graph.1.@type',
                    'BreadcrumbList'
                )
                ->where(
                    'seo.structuredData.@graph.2.@type',
                    'Organization'
                )
                ->etc()
        );
    }

    public function test_robots_exposes_sitemap_and_blocks_private_confirmation_routes(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();

        $response->assertHeader(
            'Content-Type',
            'text/plain; charset=UTF-8'
        );

        $response->assertSee(
            'User-agent: *'
        );

        $response->assertSee(
            'Allow: /'
        );

        $response->assertSee(
            '/sitemap.xml'
        );

        $response->assertSee('Disallow: /admin');
        $response->assertSee('Disallow: /dashboard');
        $response->assertSee('Disallow: /reservation-confirmed/');
        $response->assertSee('Disallow: /nl/reservation-confirmed/');
        $response->assertSee('Disallow: /en/reservation-confirmed/');
        $response->assertSee('Disallow: /de/reservierung-bestaetigt/');
    }

    public function test_public_collection_uses_requested_locale_canonical_and_all_alternates(): void
    {
        foreach ([
            'watches.index' => ['locale' => 'fr_BE', 'canonical' => route('watches.index')],
            'nl.watches.index' => ['locale' => 'nl_BE', 'canonical' => route('nl.watches.index')],
            'en.watches.index' => ['locale' => 'en_BE', 'canonical' => route('en.watches.index')],
            'de.watches.index' => ['locale' => 'de_BE', 'canonical' => route('de.watches.index')],
        ] as $routeName => $expected) {
            $this->get(route($routeName))
                ->assertOk()
                ->assertInertia(
                    fn (Assert $page) => $page
                        ->where('locale', $expected['locale'])
                        ->where('seo.locale', $expected['locale'])
                        ->where(
                            'seo.language',
                            match ($expected['locale']) {
                                'nl_BE' => 'nl',
                                'en_BE' => 'en',
                                'de_BE' => 'de',
                                default => 'fr',
                            }
                        )
                        ->where('seo.canonical', $expected['canonical'])
                        ->where('seo.alternates.0.hreflang', 'fr')
                        ->where('seo.alternates.1.hreflang', 'nl')
                        ->where('seo.alternates.2.hreflang', 'en')
                        ->where('seo.alternates.3.hreflang', 'de')
                        ->where('seo.alternates.4.hreflang', 'x-default')
                        ->where(
                            'seo.structuredData.@graph.0.@type',
                            'WebSite'
                        )
                        ->where(
                            'seo.structuredData.@graph.1.@type',
                            'CollectionPage'
                        )
                        ->etc()
                );
        }
    }

    public function test_diamond_vs_moissanite_guide_is_localized_and_honest(): void
    {
        foreach ([
            'guides.diamond-vs-moissanite' => 'fr_BE',
            'nl.guides.diamond-vs-moissanite' => 'nl_BE',
            'en.guides.diamond-vs-moissanite' => 'en_BE',
            'de.guides.diamond-vs-moissanite' => 'de_BE',
        ] as $routeName => $locale) {
            $this->get(route($routeName))
                ->assertOk()
                ->assertInertia(
                    fn (Assert $page) => $page
                        ->component('Guides/DiamondVsMoissanite')
                        ->where('locale', $locale)
                        ->where('seo.type', 'article')
                        ->where('seo.alternates.0.hreflang', 'fr')
                        ->where('seo.alternates.1.hreflang', 'nl')
                        ->where('seo.alternates.2.hreflang', 'en')
                        ->where('seo.alternates.3.hreflang', 'de')
                        ->where('seo.alternates.4.hreflang', 'x-default')
                        ->where(
                            'seo.structuredData.@graph.0.@type',
                            'Article'
                        )
                        ->where(
                            'seo.structuredData.@graph.1.@type',
                            'BreadcrumbList'
                        )
                        ->where(
                            'guide.title',
                            trans(
                                'guides.diamond_vs_moissanite.title',
                                [],
                                $locale
                            )
                        )
                        ->where(
                            'guide.vvs_title',
                            trans(
                                'guides.diamond_vs_moissanite.vvs_title',
                                [],
                                $locale
                            )
                        )
                        ->where('guide.rows.3.label', 'VVS')
                        ->etc()
                );
        }
    }

    public function test_guide_uses_human_copy_and_explains_report_fields(): void
    {
        $frenchGuide = trans(
            'guides.diamond_vs_moissanite',
            [],
            'fr_BE'
        );
        $dutchGuide = trans(
            'guides.diamond_vs_moissanite',
            [],
            'nl_BE'
        );

        $this->assertIsArray($frenchGuide);
        $this->assertIsArray($dutchGuide);
        $this->assertArrayNotHasKey('answer_title', $frenchGuide);
        $this->assertArrayNotHasKey('answer_title', $dutchGuide);
        $this->assertSame(
            'Que regarder sur un rapport GRA ?',
            $frenchGuide['report_title']
        );
        $this->assertSame(
            'Waar let je op bij een GRA-rapport?',
            $dutchGuide['report_title']
        );
        $this->assertCount(12, $frenchGuide['report_fields']);
        $this->assertCount(12, $dutchGuide['report_fields']);
        $this->assertStringContainsString(
            'plus de deux fois le feu du diamant',
            $frenchGuide['answer']
        );
    }

    public function test_geo_copy_explains_the_business_without_exposing_keyword_strategy(): void
    {
        $frenchIntents = trans('seo_intents', [], 'fr_BE');

        $this->assertIsArray($frenchIntents);

        $serialized = json_encode(
            $frenchIntents,
            JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
        );

        $this->assertStringNotContainsString(
            'utilise les recherches autour du diamant',
            $serialized
        );
        $this->assertStringNotContainsString(
            'servent ici à répondre à une intention de recherche',
            $serialized
        );

        $belgiumAnswer = $frenchIntents['belgium']['answer'];

        $this->assertStringContainsString('Belgique', $belgiumAnswer);
        $this->assertStringNotContainsString('25 %', $serialized);
        $this->assertStringNotContainsString('75 %', $serialized);
        $this->assertStringNotContainsString('acompte', mb_strtolower($serialized));
        $this->assertArrayHasKey('france', $frenchIntents);
    }

    public function test_public_copy_does_not_publish_deposit_or_split_payment_terms(): void
    {
        foreach (['fr_BE', 'nl_BE', 'en_BE', 'de_BE'] as $locale) {
            $publicCopy = json_encode(
                [
                    trans('site', [], $locale),
                    trans('seo_intents', [], $locale),
                ],
                JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
            );

            $normalized = mb_strtolower($publicCopy);

            foreach ([
                'acompte',
                'voorschot',
                'deposit',
                'anzahlung',
                '25 %',
                '25%',
                '75 %',
                '75%',
            ] as $forbidden) {
                $this->assertStringNotContainsString(
                    $forbidden,
                    $normalized,
                    $locale.' public copy exposes '.$forbidden
                );
            }
        }
    }

    public function test_about_schema_describes_brand_and_service_area(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('seo.structuredData.@type', 'Organization')
                    ->where('seo.structuredData.name', 'VVS FLAWLESS')
                    ->where('seo.structuredData.areaServed.0.name', 'Belgium')
                    ->where('seo.structuredData.areaServed.1.name', 'France')
                    ->where('seo.structuredData.areaServed.2.name', 'Germany')
                    ->where('seo.structuredData.areaServed.3.name', 'Netherlands')
                    ->etc()
            );
    }

    public function test_market_guides_are_public_self_canonical_and_region_specific(): void
    {
        foreach ([
            'guides.france' => ['locale' => 'fr_BE', 'language' => 'fr-FR'],
            'de.guides.germany' => ['locale' => 'de_BE', 'language' => 'de-DE'],
            'nl.guides.netherlands' => ['locale' => 'nl_BE', 'language' => 'nl-NL'],
        ] as $routeName => $expected) {
            $response = $this->get(route($routeName));

            $response
                ->assertOk()
                ->assertSee(
                    'lang="'.$expected['language'].'"',
                    false
                )
                ->assertInertia(
                    fn (Assert $page) => $page
                        ->component('Guides/SeoIntent')
                        ->where('locale', $expected['locale'])
                        ->where('seo.canonical', route($routeName))
                        ->where('seo.alternates', [])
                        ->where(
                            'seo.structuredData.@graph.0.inLanguage',
                            $expected['language']
                        )
                        ->etc()
                );
        }
    }

    public function test_belgium_guide_keeps_regional_hreflang(): void
    {
        $this->get(route('guides.belgium'))
            ->assertOk()
            ->assertSee('lang="fr-BE"', false)
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('seo.alternates.0.hreflang', 'fr-BE')
                    ->where('seo.alternates.1.hreflang', 'nl-BE')
                    ->where('seo.alternates.2.hreflang', 'en-BE')
                    ->where('seo.alternates.3.hreflang', 'de-BE')
                    ->where('seo.alternates.4.hreflang', 'x-default')
                    ->etc()
            );
    }

    public function test_public_watch_copy_is_localized_in_all_four_languages(): void
    {
        $watch = new Watch([
            'name' => 'Nom brut en base',
            'price' => 950,
            'description' => 'Description brute en base.',
            'availability' => 'Sur commande',
        ]);

        $watch->id = 42;
        $watch->save();

        foreach ([
            'watches.show' => 'fr_BE',
            'nl.watches.show' => 'nl_BE',
            'en.watches.show' => 'en_BE',
            'de.watches.show' => 'de_BE',
        ] as $routeName => $locale) {
            $this->get(route($routeName, $watch))
                ->assertOk()
                ->assertInertia(
                    fn (Assert $page) => $page
                        ->where(
                            'watch.name',
                            trans('watches.42.name', [], $locale)
                        )
                        ->where(
                            'watch.description',
                            trans(
                                'watches.42.description',
                                [],
                                $locale
                            )
                        )
                        ->where('seo.alternates.2.hreflang', 'en')
                        ->where('seo.alternates.3.hreflang', 'de')
                        ->where('seo.alternates.4.hreflang', 'x-default')
                        ->etc()
                );
        }
    }
}
