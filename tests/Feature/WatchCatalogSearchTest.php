<?php

namespace Tests\Feature;

use App\Models\Watch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WatchCatalogSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function createWatch(int $id, array $overrides = []): Watch
    {
        $watch = new Watch(array_merge([
            'name' => 'Montre VVS test '.$id,
            'slug' => 'watch-test-'.$id,
            'price' => 900,
            'promo_price' => null,
            'description' => 'Montre de test pour la recherche catalogue.',
            'availability' => 'Sur commande',
            'image' => '/images/watches/test-'.$id.'.webp',
            'japanese_price' => 700,
            'japanese_promo_price' => null,
            'swiss_price' => 950,
            'swiss_promo_price' => null,
            'stock_quantity' => 1,
        ], $overrides));

        $watch->id = $id;
        $watch->save();

        return $watch;
    }

    private function createPatekGreenWatch(): Watch
    {
        return $this->createWatch(47, [
            'name' => 'Nom DB non public',
            'slug' => 'geometrique-or-jaune-cadran-vert',
            'description' => 'Description DB de secours.',
            'image' => '/images/watches/catalog/015-geometrique-bicolore/05-green-gold-front.webp',
        ]);
    }

    public function test_backend_aliases_find_watch_47_without_renaming_it(): void
    {
        $this->createPatekGreenWatch();

        foreach ([
            'Patek',
            'Patek Philippe',
            'Nautilus',
            'Patek green',
            'Patek gold green',
            'VVS-47',
        ] as $query) {
            $this->get(route('watches.index', ['q' => $query]))
                ->assertOk()
                ->assertInertia(
                    fn (Assert $page) => $page
                        ->component('Watches/Index')
                        ->has('watches', 1)
                        ->where('watches.0.id', 47)
                        ->where(
                            'watches.0.name',
                            '41 mm · Géométrique or jaune, cadran vert'
                        )
                        ->where('filters.q', $query)
                        ->etc()
                );
        }
    }

    public function test_third_party_search_metadata_is_not_exposed_to_frontend_props(): void
    {
        $this->createPatekGreenWatch();

        $this->get(route('watches.index'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Watches/Index')
                    ->missing('filterOptions.families')
                    ->missing('filters.family')
                    ->where(
                        'filterOptions.models',
                        fn ($models): bool => collect($models)->every(
                            fn ($model): bool =>
                                ! array_key_exists('families', (array) $model)
                                && ! array_key_exists('searchText', (array) $model)
                        )
                    )
                    ->where(
                        'catalogModels',
                        fn ($models): bool => collect($models)->every(
                            fn ($model): bool =>
                                ! array_key_exists('families', (array) $model)
                                && ! array_key_exists('searchText', (array) $model)
                        )
                    )
                    ->etc()
            );
    }

    public function test_neutral_model_filter_still_works(): void
    {
        $this->createPatekGreenWatch();
        $this->createWatch(90);

        $this->get(route('watches.index', ['model' => 47]))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Watches/Index')
                    ->has('watches', 1)
                    ->where('watches.0.id', 47)
                    ->where('filters.model', '47')
                    ->etc()
            );
    }

    public function test_third_party_aliases_never_replace_vvs_product_brand_in_structured_data(): void
    {
        $watch = $this->createPatekGreenWatch();

        $this->get(route('watches.show', $watch))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Watches/Show')
                    ->where(
                        'watch.name',
                        '41 mm · Géométrique or jaune, cadran vert'
                    )
                    ->where(
                        'seo.structuredData',
                        fn ($data): bool =>
                            data_get($data, '@graph.0.name')
                                === '41 mm · Géométrique or jaune, cadran vert'
                            && data_get($data, '@graph.0.brand.name')
                                === 'VVS FLAWLESS'
                    )
                    ->etc()
            );
    }

    public function test_pagination_keeps_search_query_string(): void
    {
        for ($index = 0; $index < 13; $index++) {
            $this->createWatch(100 + $index, [
                'name' => 'Recherche pagination '.$index,
                'slug' => 'recherche-pagination-'.$index,
                'description' => 'Recherche pagination test.',
            ]);
        }

        $this->get(route('watches.index', ['q' => 'recherche']))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('pagination.currentPage', 1)
                    ->where(
                        'pagination.nextUrl',
                        fn ($url): bool =>
                            is_string($url)
                            && str_contains($url, 'q=recherche')
                            && str_contains($url, 'page=2')
                    )
                    ->etc()
            );
    }
}