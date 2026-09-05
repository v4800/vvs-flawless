<?php

namespace Tests\Feature;

use App\Models\Watch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_is_public_and_contains_collection(): void
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

        $response->assertSee(
            '/watches',
            false
        );

        $response->assertSee(
            route('watches.show', $watch),
            false
        );

        $response->assertSee(
            route('nl.watches.index'),
            false
        );

        $response->assertSee(
            route('nl.watches.show', $watch),
            false
        );

        $response->assertSee(route('about'), false);
        $response->assertSee(route('nl.about'), false);
        $response->assertSee(
            route('guides.diamond-vs-moissanite'),
            false
        );
        $response->assertSee(
            route('nl.guides.diamond-vs-moissanite'),
            false
        );

        $response->assertSee('hreflang="nl-BE"', false);

        $response->assertSee(
            $watch->updated_at->toAtomString(),
            false
        );

        $response->assertDontSee(
            '/dashboard',
            false
        );
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

    public function test_robots_exposes_sitemap(): void
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

        $response->assertSee('Disallow: /dashboard');
        $response->assertSee('Disallow: /reservation-confirmed/');
    }

    public function test_public_collection_uses_the_requested_locale_and_alternates(): void
    {
        $this->get(route('watches.index'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('locale', 'fr_BE')
                    ->where('seo.locale', 'fr_BE')
                    ->where('seo.alternates.1.hreflang', 'nl-BE')
                    ->etc()
            );

        $this->get(route('nl.watches.index'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('locale', 'nl_BE')
                    ->where('seo.locale', 'nl_BE')
                    ->where('seo.alternates.0.hreflang', 'fr-BE')
                    ->etc()
            );
    }

    public function test_diamond_vs_moissanite_guide_is_localized_and_honest(): void
    {
        $this->get(route('guides.diamond-vs-moissanite'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Guides/DiamondVsMoissanite')
                    ->where('locale', 'fr_BE')
                    ->where('seo.type', 'article')
                    ->where('seo.alternates.1.hreflang', 'nl-BE')
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
                    ->where(
                        'guide.vvs_title',
                        trans(
                            'guides.diamond_vs_moissanite.vvs_title',
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
                    ->where(
                        'guide.title',
                        trans(
                            'guides.diamond_vs_moissanite.title',
                            [],
                            'nl_BE'
                        )
                    )
                    ->where(
                        'guide.vvs_title',
                        trans(
                            'guides.diamond_vs_moissanite.vvs_title',
                            [],
                            'nl_BE'
                        )
                    )
                    ->where('guide.rows.3.label', 'VVS')
                    ->etc()
            );
    }

    public function test_public_watch_copy_is_localized_in_french_and_dutch(): void
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
                        trans(
                            'watches.42.description',
                            [],
                            'fr_BE'
                        )
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
                        trans(
                            'watches.42.description',
                            [],
                            'nl_BE'
                        )
                    )
                    ->etc()
            );
    }
}
