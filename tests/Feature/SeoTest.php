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
    }
}
