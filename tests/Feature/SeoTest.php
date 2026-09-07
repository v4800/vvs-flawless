<?php

namespace Tests\Feature;

use App\Models\Watch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_is_public_and_contains_all_localized_pages(): void
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

        $response->assertSee(route('watches.index'), false);
        $response->assertSee(route('nl.watches.index'), false);
        $response->assertSee(route('en.watches.index'), false);
        $response->assertSee(route('watches.show', $watch), false);
        $response->assertSee(route('nl.watches.show', $watch), false);
        $response->assertSee(route('en.watches.show', $watch), false);
        $response->assertSee(route('about'), false);
        $response->assertSee(route('nl.about'), false);
        $response->assertSee(route('en.about'), false);
        $response->assertSee(
            route('guides.diamond-vs-moissanite'),
            false
        );
        $response->assertSee(
            route('nl.guides.diamond-vs-moissanite'),
            false
        );
        $response->assertSee(
            route('en.guides.diamond-vs-moissanite'),
            false
        );
        $response->assertSee(route('guides.vvs-watch'), false);
        $response->assertSee(route('en.guides.men-women'), false);
        $response->assertSee(route('nl.guides.belgium'), false);
        $response->assertSee('hreflang="nl-BE"', false);
        $response->assertSee('hreflang="en-BE"', false);
        $response->assertSee(
            $watch->updated_at->toAtomString(),
            false
        );
        $response->assertDontSee('/dashboard', false);
    }

    public function test_made_to_order_watch_uses_preorder_structured_data(): void
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
                ->component('Watches/Show')
                ->where(
                    'seo.structuredData.@graph.0.offers.0.availability',
                    'https://schema.org/PreOrder'
                )
                ->where(
                    'seo.structuredData.@graph.0.offers.1.availability',
                    'https://schema.org/PreOrder'
                )
                ->where(
                    'seo.structuredData.@graph.1.@type',
                    'BreadcrumbList'
                )
                ->etc()
        );
    }

    public function test_configured_product_gallery_is_shared_and_used_in_schema(): void
    {
        $watch = new Watch([
            'name' => 'Montre galerie',
            'price' => 950,
            'description' => 'Description galerie.',
            'availability' => 'Sur commande',
            'japanese_price' => 950,
            'swiss_price' => 1250,
            'image' => '/images/watches/classique-bleu-roi.webp',
        ]);
        $watch->id = 45;
        $watch->save();

        $this->get(route('watches.show', $watch))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where(
                        'watch.gallery_images.0',
                        '/images/watches/classique-bleu-roi-vvs/front-three-quarter.webp'
                    )
                    ->where(
                        'watch.gallery_images.3',
                        '/images/watches/classique-bleu-roi-vvs/back.webp'
                    )
                    ->where(
                        'seo.structuredData.@graph.0.image.0',
                        url('/images/watches/classique-bleu-roi-vvs/front-three-quarter.webp')
                    )
                    ->etc()
            );
    }

    public function test_robots_exposes_sitemap_and_hides_confirmation_routes(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertHeader(
            'Content-Type',
            'text/plain; charset=UTF-8'
        );
        $response->assertSee('User-agent: *');
        $response->assertSee('Allow: /');
        $response->assertSee('/sitemap.xml');
        $response->assertSee('Disallow: /dashboard');
        $response->assertSee('Disallow: /reservation-confirmed/');
        $response->assertSee('Disallow: /nl/reservation-confirmed/');
        $response->assertSee('Disallow: /en/reservation-confirmed/');
    }

    public function test_public_collection_uses_fr_nl_and_en_locales_with_alternates(): void
    {
        $this->get(route('watches.index'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('locale', 'fr_BE')
                    ->where('seo.locale', 'fr_BE')
                    ->where('seo.alternates.1.hreflang', 'nl-BE')
                    ->where('seo.alternates.2.hreflang', 'en-BE')
                    ->etc()
            );

        $this->get(route('nl.watches.index'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('locale', 'nl_BE')
                    ->where('seo.locale', 'nl_BE')
                    ->where('seo.alternates.0.hreflang', 'fr-BE')
                    ->where('seo.alternates.2.hreflang', 'en-BE')
                    ->etc()
            );

        $this->get(route('en.watches.index'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('locale', 'en_BE')
                    ->where('seo.locale', 'en_BE')
                    ->where('seo.alternates.0.hreflang', 'fr-BE')
                    ->where('seo.alternates.1.hreflang', 'nl-BE')
                    ->etc()
            );
    }

    public function test_diamond_vs_moissanite_guide_is_localized_in_three_languages(): void
    {
        $this->get(route('guides.diamond-vs-moissanite'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Guides/DiamondVsMoissanite')
                    ->where('locale', 'fr_BE')
                    ->where('seo.type', 'article')
                    ->where('seo.alternates.1.hreflang', 'nl-BE')
                    ->where('seo.alternates.2.hreflang', 'en-BE')
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
                            'fr_BE'
                        )
                    )
                    ->where('guide.rows.3.label', 'VVS')
                    ->etc()
            );

        $this->get(route('nl.guides.diamond-vs-moissanite'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Guides/DiamondVsMoissanite')
                    ->where('locale', 'nl_BE')
                    ->where('seo.type', 'article')
                    ->where('seo.alternates.0.hreflang', 'fr-BE')
                    ->where('seo.alternates.2.hreflang', 'en-BE')
                    ->where('guide.rows.3.label', 'VVS')
                    ->etc()
            );

        $this->get(route('en.guides.diamond-vs-moissanite'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Guides/DiamondVsMoissanite')
                    ->where('locale', 'en_BE')
                    ->where('seo.type', 'article')
                    ->where('seo.alternates.0.hreflang', 'fr-BE')
                    ->where('seo.alternates.1.hreflang', 'nl-BE')
                    ->where('guide.rows.3.label', 'VVS')
                    ->etc()
            );
    }

    public function test_guide_keeps_report_fields_in_all_languages(): void
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
        $englishGuide = trans(
            'guides.diamond_vs_moissanite',
            [],
            'en_BE'
        );

        $this->assertIsArray($frenchGuide);
        $this->assertIsArray($dutchGuide);
        $this->assertIsArray($englishGuide);
        $this->assertArrayNotHasKey('answer_title', $frenchGuide);
        $this->assertArrayNotHasKey('answer_title', $dutchGuide);
        $this->assertArrayNotHasKey('answer_title', $englishGuide);
        $this->assertSame(
            'Que regarder sur un rapport GRA ?',
            $frenchGuide['report_title']
        );
        $this->assertSame(
            'Waar let je op bij een GRA-rapport?',
            $dutchGuide['report_title']
        );
        $this->assertSame(
            'What should you check on a GRA report?',
            $englishGuide['report_title']
        );
        $this->assertCount(12, $frenchGuide['report_fields']);
        $this->assertCount(12, $dutchGuide['report_fields']);
        $this->assertCount(12, $englishGuide['report_fields']);
    }

    public function test_public_watch_copy_is_localized_in_three_languages(): void
    {
        $watch = new Watch([
            'name' => 'Nom brut en base',
            'price' => 950,
            'description' => 'Description brute en base.',
            'availability' => 'Sur commande',
        ]);

        $watch->id = 42;
        $watch->save();

        $this->get(route('watches.show', $watch))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where(
                        'watch.name',
                        trans('watches.42.name', [], 'fr_BE')
                    )
                    ->where(
                        'watch.description',
                        trans('watches.42.description', [], 'fr_BE')
                    )
                    ->etc()
            );

        $this->get(route('nl.watches.show', $watch))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where(
                        'watch.name',
                        trans('watches.42.name', [], 'nl_BE')
                    )
                    ->where(
                        'watch.description',
                        trans('watches.42.description', [], 'nl_BE')
                    )
                    ->etc()
            );

        $this->get(route('en.watches.show', $watch))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where(
                        'watch.name',
                        trans('watches.42.name', [], 'en_BE')
                    )
                    ->where(
                        'watch.description',
                        trans('watches.42.description', [], 'en_BE')
                    )
                    ->etc()
            );
    }
}
