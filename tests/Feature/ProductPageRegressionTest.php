<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Watch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductPageRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function createWatch(array $overrides = []): Watch
    {
        return Watch::query()->create(array_merge([
            'name' => 'Montre régression VVS',
            'price' => 1290,
            'promo_price' => 990,
            'description' => 'Montre utilisée pour verrouiller le parcours produit.',
            'availability' => 'Sur commande',
            'image' => '/images/watches/test-regression.webp',
            'japanese_price' => 1190,
            'japanese_promo_price' => 890,
            'swiss_price' => 1590,
            'swiss_promo_price' => 1290,
            'stock_quantity' => 2,
        ], $overrides));
    }

    public function test_product_page_contract_is_stable_in_all_locales_and_for_both_movements(): void
    {
        $watch = $this->createWatch();

        foreach ([
            'watches.show' => ['locale' => 'fr_BE', 'canonical' => route('watches.show', $watch)],
            'nl.watches.show' => ['locale' => 'nl_BE', 'canonical' => route('nl.watches.show', $watch)],
            'en.watches.show' => ['locale' => 'en_BE', 'canonical' => route('en.watches.show', $watch)],
        ] as $routeName => $expected) {
            $baseUrl = route($routeName, $watch);

            $this->get($baseUrl)
                ->assertOk()
                ->assertInertia(
                    fn (Assert $page) => $page
                        ->component('Watches/Show')
                        ->where('locale', $expected['locale'])
                        ->where('selectedMovement', 'Japonais')
                        ->where('watch.id', $watch->id)
                        ->where(
                            'watch.japanese_price',
                            fn ($price): bool => (float) $price === 1190.0
                        )
                        ->where(
                            'watch.japanese_promo_price',
                            fn ($price): bool => (float) $price === 890.0
                        )
                        ->where(
                            'watch.swiss_price',
                            fn ($price): bool => (float) $price === 1590.0
                        )
                        ->where(
                            'watch.swiss_promo_price',
                            fn ($price): bool => (float) $price === 1290.0
                        )
                        ->where('seo.canonical', $expected['canonical'])
                        ->has('gallery')
                        ->has('relatedWatches')
                        ->etc()
                );

            $this->get($baseUrl.'?movement=Suisse')
                ->assertOk()
                ->assertInertia(
                    fn (Assert $page) => $page
                        ->component('Watches/Show')
                        ->where('locale', $expected['locale'])
                        ->where('selectedMovement', 'Suisse')
                        ->where('seo.canonical', $expected['canonical'])
                        ->etc()
                );
        }
    }

    public function test_tampered_movement_query_falls_back_to_japanese(): void
    {
        $watch = $this->createWatch();

        foreach (['Swiss', 'SUISSE', 'HACKED', ''] as $movement) {
            $this->get(route('watches.show', $watch).'?movement='.urlencode($movement))
                ->assertOk()
                ->assertInertia(
                    fn (Assert $page) => $page
                        ->where('selectedMovement', 'Japonais')
                        ->etc()
                );
        }
    }

    public function test_localized_reservations_use_server_prices_and_localized_signed_confirmation_urls(): void
    {
        Mail::fake();

        $watch = $this->createWatch();

        $cases = [
            [
                'route' => 'reservations.store',
                'movement' => 'Japonais',
                'email' => 'fr-regression@example.com',
                'expected_price' => 890.0,
                'expected_path_prefix' => '/reservation-confirmed/',
            ],
            [
                'route' => 'nl.reservations.store',
                'movement' => 'Suisse',
                'email' => 'nl-regression@example.com',
                'expected_price' => 1290.0,
                'expected_path_prefix' => '/nl/reservation-confirmed/',
            ],
            [
                'route' => 'en.reservations.store',
                'movement' => 'Japonais',
                'email' => 'en-regression@example.com',
                'expected_price' => 890.0,
                'expected_path_prefix' => '/en/reservation-confirmed/',
            ],
        ];

        foreach ($cases as $case) {
            $response = $this->withHeader('X-Inertia', 'true')->post(
                route($case['route']),
                [
                    'watch_id' => $watch->id,
                    'movement' => $case['movement'],
                    'customer_name' => 'Client Régression',
                    'email' => $case['email'],
                    'phone' => '0470000042',
                    'city' => 'Liège',
                    'delivery_method' => 'Remise en main propre',
                    'message' => 'Test du parcours produit.',
                    'confirmation' => true,
                ]
            );

            $response->assertStatus(409);

            $location = $response->headers->get('X-Inertia-Location');

            $this->assertIsString($location);
            $this->assertStringContainsString('signature=', $location);
            $this->assertStringStartsWith(
                $case['expected_path_prefix'],
                (string) parse_url($location, PHP_URL_PATH)
            );

            $reservation = Reservation::query()
                ->where('email', $case['email'])
                ->firstOrFail();

            $this->assertSame($case['movement'], $reservation->movement);
            $this->assertSame($case['expected_price'], (float) $reservation->price);
            $this->assertSame('Nouvelle demande', $reservation->status);
        }
    }

    public function test_reservation_fails_closed_when_selected_movement_has_no_price(): void
    {
        Mail::fake();

        $watch = $this->createWatch([
            'swiss_price' => null,
            'swiss_promo_price' => null,
        ]);

        $response = $this->post(route('reservations.store'), [
            'watch_id' => $watch->id,
            'movement' => 'Suisse',
            'customer_name' => 'Client sans prix',
            'email' => 'no-price@example.com',
            'phone' => '0470000043',
            'city' => 'Liège',
            'delivery_method' => 'Livraison',
            'confirmation' => true,
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('reservations', [
            'email' => 'no-price@example.com',
        ]);
    }
}
