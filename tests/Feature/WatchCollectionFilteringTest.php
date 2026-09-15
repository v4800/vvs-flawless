<?php

namespace Tests\Feature;

use App\Models\Watch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WatchCollectionFilteringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_collection_is_paginated_on_the_server_by_twelve(): void
    {
        foreach (range(1, 13) as $index) {
            Watch::query()->create([
                'name' => 'Model '.$index,
                'description' => 'Collection test '.$index,
                'price' => 500 + $index,
                'japanese_price' => 500 + $index,
                'availability' => 'Sur commande',
                'image' => '/images/watches/test-'.$index.'.webp',
            ]);
        }

        $this->get(route('watches.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('watches', 12)
                ->where('pagination.currentPage', 1)
                ->where('pagination.lastPage', 2)
                ->where('pagination.total', 13)
                ->etc());

        $this->get(route('watches.index', ['page' => 2]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('watches', 1)
                ->where('pagination.currentPage', 2)
                ->where('pagination.total', 13)
                ->etc());
    }

    public function test_search_and_filters_are_applied_before_pagination(): void
    {
        $wanted = Watch::query()->create([
            'name' => 'Chronographe Vert',
            'description' => 'Cadran vert géométrique',
            'price' => 900,
            'japanese_price' => 900,
            'swiss_price' => 1200,
            'availability' => 'Sur commande',
            'image' => '/images/watches/green.webp',
        ]);

        Watch::query()->create([
            'name' => 'Carrée Noire',
            'description' => 'Cadran noir',
            'price' => 650,
            'japanese_price' => 650,
            'availability' => 'Disponible',
            'image' => '/images/watches/black.webp',
        ]);

        $this->get(route('watches.index', [
            'q' => 'vert',
            'model' => $wanted->id,
            'movement' => 'swiss',
            'availability' => 'Sur commande',
            'price_min' => 1150,
            'price_max' => 1250,
            'sort' => 'price_asc',
        ]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('watches', 1)
                ->where('watches.0.id', $wanted->id)
                ->where('filters.q', 'vert')
                ->where('filters.model', (string) $wanted->id)
                ->where('filters.movement', 'swiss')
                ->where('filters.availability', 'Sur commande')
                ->where('pagination.total', 1)
                ->etc());
    }

    public function test_pagination_urls_keep_the_active_query(): void
    {
        foreach (range(1, 13) as $index) {
            Watch::query()->create([
                'name' => 'Gold Model '.$index,
                'description' => 'Gold collection',
                'price' => 700,
                'japanese_price' => 700,
                'availability' => 'Sur commande',
                'image' => '/images/watches/gold-'.$index.'.webp',
            ]);
        }

        $this->get(route('watches.index', [
            'q' => 'Gold',
            'sort' => 'name',
        ]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where(
                    'pagination.nextUrl',
                    fn (?string $url) => is_string($url)
                        && str_contains($url, 'q=Gold')
                        && str_contains($url, 'sort=name')
                        && str_contains($url, 'page=2')
                )
                ->etc());
    }

    public function test_filtered_collection_is_noindex_and_uses_the_base_canonical(): void
    {
        Watch::query()->create([
            'name' => 'Gold SEO Model',
            'description' => 'SEO filter test',
            'price' => 700,
            'japanese_price' => 700,
            'availability' => 'Sur commande',
            'image' => '/images/watches/seo-filter.webp',
        ]);

        $this->get(route('watches.index', ['q' => 'Gold']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('seo.robots', 'noindex,follow')
                ->where('seo.canonical', route('watches.index'))
                ->etc());
    }

    public function test_second_unfiltered_page_has_its_own_canonical(): void
    {
        foreach (range(1, 13) as $index) {
            Watch::query()->create([
                'name' => 'SEO Page Model '.$index,
                'description' => 'SEO pagination test',
                'price' => 700 + $index,
                'japanese_price' => 700 + $index,
                'availability' => 'Sur commande',
                'image' => '/images/watches/seo-page-'.$index.'.webp',
            ]);
        }

        $this->get(route('watches.index', ['page' => 2]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('seo.robots', 'index,follow')
                ->where(
                    'seo.canonical',
                    route('watches.index', ['page' => 2])
                )
                ->etc());
    }
}
