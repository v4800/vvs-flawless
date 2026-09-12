<?php

namespace Tests\Feature;

use App\Models\Watch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WatchSlugTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function createWatch(string $name = '41 mm · Bleue, chiffres romains'): Watch
    {
        return Watch::query()->create([
            'name' => $name,
            'price' => 950,
            'description' => 'Montre de test pour les URLs publiques.',
            'availability' => 'Sur commande',
            'japanese_price' => 950,
            'swiss_price' => 1250,
        ]);
    }

    public function test_watch_gets_a_stable_unique_slug(): void
    {
        $watch = $this->createWatch();

        $this->assertSame(
            '41-mm-bleue-chiffres-romains',
            $watch->slug
        );

        $watch->update([
            'name' => 'Nom édité après publication',
        ]);

        $this->assertSame(
            '41-mm-bleue-chiffres-romains',
            $watch->fresh()->slug
        );

        $duplicate = $this->createWatch();

        $this->assertSame(
            '41-mm-bleue-chiffres-romains-2',
            $duplicate->slug
        );
    }

    public function test_slug_routes_are_canonical_in_all_locales(): void
    {
        $watch = $this->createWatch();

        foreach ([
            'watches.show',
            'nl.watches.show',
            'en.watches.show',
        ] as $routeName) {
            $canonical = route($routeName, $watch);

            $this->assertStringContainsString(
                '/'.$watch->slug,
                $canonical
            );

            $this->get($canonical)
                ->assertOk()
                ->assertInertia(
                    fn (Assert $page) => $page
                        ->where('watch.id', $watch->id)
                        ->where('watch.slug', $watch->slug)
                        ->where('seo.canonical', $canonical)
                        ->etc()
                );
        }
    }

    public function test_legacy_id_urls_redirect_permanently_and_keep_query_parameters(): void
    {
        $watch = $this->createWatch();

        foreach ([
            ['legacy' => 'watches.legacy', 'show' => 'watches.show'],
            ['legacy' => 'nl.watches.legacy', 'show' => 'nl.watches.show'],
            ['legacy' => 'en.watches.legacy', 'show' => 'en.watches.show'],
        ] as $routes) {
            $legacyUrl = route($routes['legacy'], [
                'watchId' => $watch->id,
                'movement' => 'Suisse',
            ]);

            $expectedUrl = route($routes['show'], [
                'watch' => $watch,
                'movement' => 'Suisse',
            ]);

            $this->get($legacyUrl)
                ->assertStatus(301)
                ->assertRedirect($expectedUrl);
        }
    }

    public function test_sitemap_only_publishes_slug_product_urls(): void
    {
        $watch = $this->createWatch();

        $response = $this->get(route('sitemap'));

        $response->assertOk();

        foreach ([
            route('watches.show', $watch),
            route('nl.watches.show', $watch),
            route('en.watches.show', $watch),
        ] as $url) {
            $response->assertSee($url, false);
        }

        foreach ([
            route('watches.legacy', ['watchId' => $watch->id]),
            route('nl.watches.legacy', ['watchId' => $watch->id]),
            route('en.watches.legacy', ['watchId' => $watch->id]),
        ] as $legacyUrl) {
            $response->assertDontSee($legacyUrl, false);
        }
    }
}
