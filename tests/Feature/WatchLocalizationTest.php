<?php

namespace Tests\Feature;

use App\Models\Watch;
use App\Support\WatchCatalog;
use App\Support\WatchSeo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Ssr\SsrState;
use Inertia\Testing\AssertableInertia as Assert;
use LogicException;
use Tests\TestCase;

class WatchLocalizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function localizedRoutes(): array
    {
        return [
            'watches.show' => 'fr_BE',
            'nl.watches.show' => 'nl_BE',
            'en.watches.show' => 'en_BE',
            'de.watches.show' => 'de_BE',
        ];
    }

    private function makeWatch(int $id): Watch
    {
        $watch = new Watch([
            'name' => 'Nom français brut '.$id,
            'price' => 950,
            'japanese_price' => 950,
            'swiss_price' => 1250,
            'description' => 'Description française brute '.$id.'.',
            'availability' => 'Sur commande',
        ]);

        $watch->id = $id;
        $watch->save();

        return $watch;
    }

    public function test_multiple_products_use_exact_locale_for_visible_copy_meta_and_json_ld(): void
    {
        foreach ([42, 47, 52] as $watchId) {
            $watch = $this->makeWatch($watchId);
            $frenchDescription = trans(
                'watches.'.$watchId.'.description',
                [],
                'fr_BE'
            );

            foreach ($this->localizedRoutes() as $routeName => $locale) {
                app()->setLocale($locale);

                $localizedWatch = app(WatchCatalog::class)
                    ->localizedWatch($watch);
                $expectedDescription = trans(
                    'watches.'.$watchId.'.description',
                    [],
                    $locale
                );
                $expectedShortDescription = trans(
                    'watches.'.$watchId.'.short_description',
                    [],
                    $locale
                );
                $expectedMeta = app(WatchSeo::class)
                    ->product($localizedWatch, [])['description'];

                // Each HTTP request gets a fresh SSR scope in production.
                // PHPUnit reuses the application container inside this test,
                // so forget the previous scoped SSR state before the next request.
                app()->forgetInstance(SsrState::class);

                $response = $this->get(route($routeName, $watch));

                $response
                    ->assertOk()
                    ->assertInertia(
                        fn (Assert $page) => $page
                            ->where('locale', $locale)
                            ->where('watch.description', $expectedDescription)
                            ->where(
                                'watch.short_description',
                                $expectedShortDescription
                            )
                            ->where('seo.description', $expectedMeta)
                            ->where(
                                'seo.structuredData.@graph.0.description',
                                $expectedMeta
                            )
                            ->etc()
                    );

                $html = $response->getContent();
                $escapedMeta = e($expectedMeta);

                $this->assertMatchesRegularExpression(
                    '/<meta\s+name="description"\s+content="'
                    .preg_quote($escapedMeta, '/')
                    .'"\s*>/s',
                    $html
                );
                $this->assertMatchesRegularExpression(
                    '/<meta\s+property="og:description"\s+content="'
                    .preg_quote($escapedMeta, '/')
                    .'"\s*>/s',
                    $html
                );
                $this->assertMatchesRegularExpression(
                    '/<meta\s+name="twitter:description"\s+content="'
                    .preg_quote($escapedMeta, '/')
                    .'"\s*>/s',
                    $html
                );
                $this->assertLessThanOrEqual(160, mb_strlen($expectedMeta));

                if ($locale !== 'fr_BE') {
                    $this->assertNotSame(
                        $frenchDescription,
                        $expectedDescription
                    );
                    $this->assertStringNotContainsString(
                        $frenchDescription,
                        $html
                    );
                }
            }
        }
    }

    public function test_foreign_locale_never_silently_falls_back_to_french_database_description(): void
    {
        $watch = $this->makeWatch(900);
        app()->setLocale('nl_BE');

        $this->expectException(LogicException::class);

        app(WatchCatalog::class)->localizedWatch($watch);
    }

    public function test_all_french_watch_translation_entries_exist_in_foreign_locales(): void
    {
        $french = trans('watches', [], 'fr_BE');

        $this->assertIsArray($french);

        foreach (['nl_BE', 'en_BE', 'de_BE'] as $locale) {
            $translated = trans('watches', [], $locale);

            $this->assertIsArray($translated);
            $this->assertArrayHasKey('presented', $translated);
            $this->assertArrayHasKey('catalog', $translated);

            foreach ($french as $key => $copy) {
                if (! is_int($key)) {
                    continue;
                }

                $this->assertArrayHasKey($key, $translated);

                foreach (['name', 'short_description', 'description'] as $field) {
                    $this->assertIsString($translated[$key][$field] ?? null);
                }

                $this->assertNotSame(
                    $copy['description'] ?? null,
                    $translated[$key]['description'] ?? null,
                    $locale.' watch '.$key.' description'
                );
            }

            foreach ($french['catalog'] as $slug => $copy) {
                $this->assertArrayHasKey($slug, $translated['catalog']);

                foreach (['name', 'short_description', 'description'] as $field) {
                    $this->assertIsString(
                        $translated['catalog'][$slug][$field] ?? null
                    );
                }

                if ($locale !== 'fr_BE') {
                    $this->assertNotSame(
                        $copy['description'] ?? null,
                        $translated['catalog'][$slug]['description'] ?? null,
                        $locale.' catalog '.$slug.' description'
                    );
                }
            }
        }
    }
}
