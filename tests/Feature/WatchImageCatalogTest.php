<?php

namespace Tests\Feature;

use App\Models\Watch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WatchImageCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_row_numbers_do_not_assign_another_models_photos(): void
    {
        foreach ([46, 47, 48, 52] as $id) {
            $watch = new Watch([
                'name' => 'Original model',
                'description' => 'Original description',
                'price' => 950,
                'availability' => 'Sur commande',
                'image' => '/images/watches/original-'.$id.'.webp',
            ]);
            $watch->id = $id;
            $watch->save();

            $this->get(route('watches.show', $watch))
                ->assertOk()
                ->assertInertia(fn (Assert $page) => $page
                    ->where('watch.image', $watch->image)
                    ->has('gallery', 0)
                    ->etc());
        }
    }

    public function test_blue_photo_updates_the_collection_product_and_seo_without_writing_to_database(): void
    {
        $original = '/images/watches/presidentielle-bleu-romains.webp';
        $photo = '/images/watches/catalog/001-blue-round/07-black-marble.webp';
        $cardPhoto = '/images/watches/catalog/001-blue-round/07-black-marble-card.webp';
        $watch = Watch::query()->create([
            'name' => 'Blue Roman dial',
            'description' => 'Original description',
            'price' => 950,
            'japanese_price' => 950,
            'availability' => 'Sur commande',
            'stock_quantity' => null,
            'image' => $original,
        ]);
        $before = $watch->fresh()->getAttributes();

        $this->get(route('watches.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('watches.0.image', $photo)
                ->where('watches.0.card_image', $cardPhoto)
                ->where('catalogModels.0.watchUrl', route('watches.show', $watch))
                ->etc());

        $this->get(route('watches.show', $watch))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('watch.image', $photo)
                ->where('gallery', [$photo])
                ->where('seo.image', url($photo))
                ->where('seo.structuredData.@graph.0.image', [url($photo)])
                ->where('seo.structuredData.@graph.0.offers.0.availability', 'https://schema.org/PreOrder')
                ->etc());

        $this->assertSame($before, $watch->fresh()->getAttributes());
        $this->assertDatabaseCount('watches', 1);
    }

    public function test_new_models_have_real_images_localized_names_and_no_invented_offers(): void
    {
        foreach (['watches.index' => 'fr_BE', 'nl.watches.index' => 'nl_BE', 'en.watches.index' => 'en_BE'] as $route => $locale) {
            $this->get(route($route))
                ->assertOk()
                ->assertInertia(fn (Assert $page) => $page
                    ->has('watches', 0)
                    ->has('catalogModels', 5)
                    ->where('catalogModels.0.name', trans('site.collection.catalog_names.blue-round', [], $locale))
                    ->where('catalogModels.3.name', trans('site.collection.catalog_names.sport-black-strap', [], $locale))
                    ->where('catalogModels.4.name', trans('site.collection.catalog_names.women-pink', [], $locale))
                    ->where('catalogModels', function ($models) {
                        foreach ($models as $model) {
                            $this->assertFileExists(public_path(ltrim($model['image'], '/')));
                            $this->assertFileExists(public_path(ltrim($model['cardImage'], '/')));
                            $this->assertNull($model['watchUrl']);
                            $this->assertArrayNotHasKey('price', $model);
                            $this->assertArrayNotHasKey('offers', $model);
                        }

                        return true;
                    })
                    ->etc());
        }

        $this->assertDatabaseCount('watches', 0);
    }

    public function test_catalog_photo_identity_overrides_a_stale_database_title(): void
    {
        $watch = new Watch([
            'name' => 'Ancien titre cadran bleu roi',
            'description' => 'Ancienne description.',
            'price' => 950,
            'japanese_price' => 950,
            'availability' => 'Sur commande',
            'image' => '/images/watches/classique-bleu-roi.webp',
        ]);
        $watch->id = 45;
        $watch->save();

        foreach ([
            'watches.show' => 'fr_BE',
            'nl.watches.show' => 'nl_BE',
            'en.watches.show' => 'en_BE',
        ] as $route => $locale) {
            $expected = trans(
                'site.collection.catalog_names.cadran-bleu-roi',
                [],
                $locale
            );

            $this->get(route($route, $watch))
                ->assertOk()
                ->assertInertia(fn (Assert $page) => $page
                    ->where('watch.name', $expected)
                    ->where(
                        'seo.title',
                        fn (string $title) => str_contains($title, $expected)
                    )
                    ->where('seo.structuredData.@graph.0.name', $expected)
                    ->etc());
        }
    }

    public function test_featured_public_catalog_images_use_optimized_webp_variants(): void
    {
        $catalog = json_decode(
            file_get_contents(resource_path('data/watch-image-catalog.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach ($catalog['watches'] as $entry) {
            if (! ($entry['featured'] ?? false)) {
                continue;
            }

            $files = [
                ...$entry['images'],
                $entry['card_image'] ?? $entry['images'][0],
            ];

            foreach (array_unique($files) as $filename) {
                $this->assertStringEndsWith('.webp', $filename);

                $path = public_path(
                    'images/watches/catalog/'.$entry['folder'].'/'.$filename
                );

                $this->assertFileExists($path);
                $this->assertLessThanOrEqual(
                    1024 * 1024,
                    filesize($path),
                    $path.' should stay below 1 MB for public delivery.'
                );
            }

            if (isset($entry['card_image'])) {
                $fullPath = public_path(
                    'images/watches/catalog/'.$entry['folder'].'/'.$entry['images'][0]
                );
                $cardPath = public_path(
                    'images/watches/catalog/'.$entry['folder'].'/'.$entry['card_image']
                );

                $this->assertLessThan(
                    filesize($fullPath),
                    filesize($cardPath),
                    $cardPath.' should stay lighter than the product image.'
                );
            }
        }
    }
}
