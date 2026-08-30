<?php

namespace Tests\Feature;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReservationSecurityTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | CRÉER UNE MONTRE DE TEST
    |--------------------------------------------------------------------------
    |
    | On utilise directement la DB afin que le test ne dépende pas
    | des fillable/factories du modèle Watch.
    |
    */

    private function createWatch(
        array $overrides = []
    ): int {
        return DB::table('watches')
            ->insertGetId(
                array_merge(
                    [
                        'name' =>
                            'Montre Test VVS',

                        /*
                        | Ancien prix général.
                        | La réservation ne doit PAS utiliser ce prix.
                        */
                        'price' =>
                            1500.00,

                        'promo_price' =>
                            1000.00,

                        'description' =>
                            'Montre utilisée pour les tests de sécurité.',

                        'availability' =>
                            'Disponible',

                        'image' =>
                            '/images/watches/test.png',

                        /*
                        | Prix réellement utilisés selon le mouvement.
                        */
                        'japanese_price' =>
                            1390.00,

                        'japanese_promo_price' =>
                            950.00,

                        'swiss_price' =>
                            1950.00,

                        'swiss_promo_price' =>
                            1350.00,

                        'stock_quantity' =>
                            3,

                        'created_at' =>
                            now(),

                        'updated_at' =>
                            now(),
                    ],

                    $overrides
                )
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CLIENT SANS COMPTE
    |--------------------------------------------------------------------------
    |
    | C'est une règle essentielle de VVS FLAWLESS :
    |
    | aucun client n'a besoin de créer un compte ou de se connecter
    | pour envoyer une réservation.
    |
    */

    public function test_guest_can_reserve_without_an_account(): void
    {
        Mail::fake();

        $watchId =
            $this->createWatch();

        $response =
            $this->post(
                route(
                    'reservations.store'
                ),
                [
                    'watch_id' =>
                        $watchId,

                    'movement' =>
                        'Japonais',

                    'customer_name' =>
                        'Client Test',

                    'email' =>
                        'client@example.com',

                    'phone' =>
                        '0470000000',

                    'city' =>
                        'Liège',

                    'message' =>
                        'Réservation de test',

                    'confirmation' =>
                        true,
                ]
            );

        /*
        | Inertia::location() utilise volontairement
        | une réponse HTTP 409 pour la navigation externe.
        */
        $response->assertStatus(409);

        $this->assertGuest();

        $this->assertDatabaseHas(
            'reservations',
            [
                'watch_id' =>
                    $watchId,

                'email' =>
                    'client@example.com',

                'movement' =>
                    'Japonais',

                'price' =>
                    950.00,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FALSIFICATION DU PRIX
    |--------------------------------------------------------------------------
    |
    | Un attaquant peut modifier le JavaScript, DevTools ou envoyer
    | directement sa propre requête HTTP.
    |
    | Même s'il envoie :
    |
    | price = 1
    |
    | Laravel doit prendre 950 € depuis la DB.
    |
    */

    public function test_client_cannot_choose_the_reservation_price(): void
    {
        Mail::fake();

        $watchId =
            $this->createWatch();

        $response =
            $this->post(
                route(
                    'reservations.store'
                ),
                [
                    'watch_id' =>
                        $watchId,

                    'movement' =>
                        'Japonais',

                    /*
                    | VALEUR MALVEILLANTE.
                    |
                    | Le contrôleur doit complètement l'ignorer.
                    */
                    'price' =>
                        1.00,

                    'customer_name' =>
                        'Price Hacker',

                    'email' =>
                        'price@example.com',

                    'phone' =>
                        '0470000001',

                    'city' =>
                        'Liège',

                    'message' =>
                        null,

                    'confirmation' =>
                        true,
                ]
            );

        $response->assertStatus(409);

        $reservation =
            Reservation::query()
                ->where(
                    'email',
                    'price@example.com'
                )
                ->firstOrFail();

        /*
        | Prix provenant de la base.
        */
        $this->assertSame(
            950.0,
            (float) $reservation->price
        );

        /*
        | Le faux prix envoyé par le navigateur
        | n'a donc pas été utilisé.
        */
        $this->assertNotSame(
            1.0,
            (float) $reservation->price
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PRIX SUISSE
    |--------------------------------------------------------------------------
    */

    public function test_swiss_price_is_also_calculated_by_the_server(): void
    {
        Mail::fake();

        $watchId =
            $this->createWatch();

        $response =
            $this->post(
                route(
                    'reservations.store'
                ),
                [
                    'watch_id' =>
                        $watchId,

                    'movement' =>
                        'Suisse',

                    'price' =>
                        1.00,

                    'customer_name' =>
                        'Swiss Test',

                    'email' =>
                        'swiss@example.com',

                    'phone' =>
                        '0470000002',

                    'city' =>
                        'Bruxelles',

                    'confirmation' =>
                        true,
                ]
            );

        $response->assertStatus(409);

        $reservation =
            Reservation::query()
                ->where(
                    'email',
                    'swiss@example.com'
                )
                ->firstOrFail();

        $this->assertSame(
            1350.0,
            (float) $reservation->price
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FAUX MOUVEMENT
    |--------------------------------------------------------------------------
    |
    | Seules deux valeurs sont autorisées :
    |
    | Japonais
    | Suisse
    |
    */

    public function test_invalid_movement_is_rejected(): void
    {
        Mail::fake();

        $watchId =
            $this->createWatch();

        $response =
            $this
                ->from(
                    "/watches/{$watchId}"
                )
                ->post(
                    route(
                        'reservations.store'
                    ),
                    [
                        'watch_id' =>
                            $watchId,

                        'movement' =>
                            'HACKED',

                        'customer_name' =>
                            'Attacker',

                        'email' =>
                            'movement@example.com',

                        'phone' =>
                            '0470000003',

                        'city' =>
                            'Liège',

                        'confirmation' =>
                            true,
                    ]
                );

        $response
            ->assertRedirect(
                "/watches/{$watchId}"
            )
            ->assertSessionHasErrors(
                'movement'
            );

        $this->assertDatabaseMissing(
            'reservations',
            [
                'email' =>
                    'movement@example.com',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MONTRE INEXISTANTE
    |--------------------------------------------------------------------------
    */

    public function test_nonexistent_watch_is_rejected(): void
    {
        Mail::fake();

        $response =
            $this
                ->from('/watches')
                ->post(
                    route(
                        'reservations.store'
                    ),
                    [
                        'watch_id' =>
                            999999999,

                        'movement' =>
                            'Japonais',

                        'customer_name' =>
                            'Fake Watch',

                        'email' =>
                            'fakewatch@example.com',

                        'phone' =>
                            '0470000004',

                        'city' =>
                            'Liège',

                        'confirmation' =>
                            true,
                    ]
                );

        $response
            ->assertRedirect(
                '/watches'
            )
            ->assertSessionHasErrors(
                'watch_id'
            );

        $this->assertDatabaseMissing(
            'reservations',
            [
                'email' =>
                    'fakewatch@example.com',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHAMPS SENSIBLES IMPOSÉS PAR LE SERVEUR
    |--------------------------------------------------------------------------
    |
    | Le client essaie ici de modifier :
    |
    | - delivery_method
    | - status
    | - reservation_number
    |
    | Le contrôleur doit ignorer ces valeurs.
    |
    */

    public function test_client_cannot_control_internal_reservation_fields(): void
    {
        Mail::fake();

        $watchId =
            $this->createWatch();

        $response =
            $this->post(
                route(
                    'reservations.store'
                ),
                [
                    'watch_id' =>
                        $watchId,

                    'movement' =>
                        'Japonais',

                    'customer_name' =>
                        'Internal Fields Attack',

                    'email' =>
                        'internal@example.com',

                    'phone' =>
                        '0470000005',

                    'city' =>
                        'Liège',

                    'confirmation' =>
                        true,

                    /*
                    |--------------------------------------------------------------------------
                    | FAUSSES VALEURS
                    |--------------------------------------------------------------------------
                    */

                    'delivery_method' =>
                        'Livraison gratuite',

                    'status' =>
                        'Payée',

                    'reservation_number' =>
                        'HACKED-123',
                ]
            );

        $response->assertStatus(409);

        $reservation =
            Reservation::query()
                ->where(
                    'email',
                    'internal@example.com'
                )
                ->firstOrFail();

        $this->assertSame(
            'Remise en main propre - point de rencontre',
            $reservation->delivery_method
        );

        $this->assertSame(
            'Nouvelle demande',
            $reservation->status
        );

        $this->assertNotSame(
            'HACKED-123',
            $reservation->reservation_number
        );

        $this->assertStringStartsWith(
            'VVS-',
            $reservation->reservation_number
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST INJECTION SQL
    |--------------------------------------------------------------------------
    |
    | Cette chaîne ressemble volontairement à une tentative classique
    | d'injection SQL.
    |
    | Comme le contrôleur utilise validation + Eloquent / PDO bindings,
    | elle doit être traitée comme du simple TEXTE.
    |
    */

    public function test_sql_injection_payload_is_treated_as_plain_data(): void
    {
        Mail::fake();

        $watchId =
            $this->createWatch();

        $payload =
            "Robert'); DROP TABLE watches;--";

        $response =
            $this->post(
                route(
                    'reservations.store'
                ),
                [
                    'watch_id' =>
                        $watchId,

                    'movement' =>
                        'Japonais',

                    'customer_name' =>
                        $payload,

                    'email' =>
                        'sql@example.com',

                    'phone' =>
                        '0470000006',

                    'city' =>
                        'Liège',

                    'confirmation' =>
                        true,
                ]
            );

        $response->assertStatus(409);

        /*
        | La table existe toujours.
        */
        $this->assertTrue(
            Schema::hasTable('watches')
        );

        /*
        | La montre existe toujours.
        */
        $this->assertDatabaseHas(
            'watches',
            [
                'id' =>
                    $watchId,
            ]
        );

        /*
        | La chaîne malveillante a simplement été stockée comme texte.
        */
        $this->assertDatabaseHas(
            'reservations',
            [
                'customer_name' =>
                    $payload,

                'email' =>
                    'sql@example.com',
            ]
        );
    }
}