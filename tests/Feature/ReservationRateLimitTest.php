<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationRateLimitTest extends TestCase
{
    use RefreshDatabase;

    private function createWatch(): int
    {
        return DB::table('watches')->insertGetId([
            'name' => 'Montre Rate Limit Test',

            'price' => 1500.00,
            'promo_price' => 950.00,

            'description' => 'Montre utilisée pour tester le rate limiting.',

            'availability' => 'Disponible',

            'image' => '/images/watches/test.png',

            'japanese_price' => 1390.00,

            'japanese_promo_price' => 950.00,

            'swiss_price' => 1950.00,

            'swiss_promo_price' => 1350.00,

            'stock_quantity' => 3,

            'created_at' => now(),

            'updated_at' => now(),
        ]);
    }

    public function test_reservation_endpoint_is_rate_limited_after_five_requests(): void
    {
        Mail::fake();

        $watchId =
            $this->createWatch();

        $payload = [
            'watch_id' => $watchId,

            'movement' => 'Japonais',

            'customer_name' => 'Rate Limit Test',

            'email' => 'ratelimit@example.com',

            'phone' => '0470000000',

            'city' => 'Liège',

            'delivery_method' => 'Remise en main propre',

            'message' => null,

            'confirmation' => true,
        ];

        /*
        |--------------------------------------------------------------------------
        | LES 5 PREMIÈRES REQUÊTES SONT ACCEPTÉES
        |--------------------------------------------------------------------------
        |
        | Avec Inertia::location(), une réservation réussie renvoie 409
        | avec l'en-tête X-Inertia-Location.
        |
        */

        for ($i = 1; $i <= 5; $i++) {
            $response =
                $this
                    ->withHeader(
                        'X-Inertia',
                        'true'
                    )
                    ->post(
                        route(
                            'reservations.store'
                        ),
                        $payload
                    );

            $response->assertStatus(409);
        }

        /*
        |--------------------------------------------------------------------------
        | CINQ RÉSERVATIONS ONT BIEN ÉTÉ CRÉÉES
        |--------------------------------------------------------------------------
        */

        $this->assertDatabaseCount(
            'reservations',
            5
        );

        /*
        |--------------------------------------------------------------------------
        | SIXIÈME REQUÊTE = BLOQUÉE
        |--------------------------------------------------------------------------
        */

        $response =
            $this
                ->withHeader(
                    'X-Inertia',
                    'true'
                )
                ->post(
                    route(
                        'reservations.store'
                    ),
                    $payload
                );

        $response->assertTooManyRequests();

        /*
        |--------------------------------------------------------------------------
        | LA SIXIÈME REQUÊTE N'A RIEN CRÉÉ
        |--------------------------------------------------------------------------
        */

        $this->assertDatabaseCount(
            'reservations',
            5
        );
    }
}
