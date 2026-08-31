<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminReservationSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function createReservation(): int
    {
        $watchId = DB::table('watches')->insertGetId([
            'name' => 'Montre Admin Test',
            'price' => 1500.00,
            'promo_price' => 950.00,
            'description' => 'Test admin',
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

        return DB::table('reservations')->insertGetId([
            'watch_id' => $watchId,
            'movement' => 'Japonais',
            'price' => 950.00,

            'customer_name' => 'Client Admin Test',
            'email' => 'admin-test@example.com',
            'phone' => '0470000000',
            'city' => 'Liège',

            'delivery_method' =>
                'Remise en main propre - point de rencontre',

            'status' =>
                'Nouvelle demande',

            'reservation_number' =>
                'VVS-ADMINTEST123',

            'message' => null,

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_guest_cannot_change_reservation_status(): void
    {
        $reservationId =
            $this->createReservation();

        $response =
            $this->patch(
                route(
                    'dashboard.reservations.status',
                    $reservationId
                ),
                [
                    'status' => 'Confirmée',
                ]
            );

        $response->assertRedirect(
            route('login')
        );

        $this->assertDatabaseHas(
            'reservations',
            [
                'id' => $reservationId,
                'status' => 'Nouvelle demande',
            ]
        );
    }

    public function test_non_admin_cannot_change_reservation_status(): void
    {
        $reservationId =
            $this->createReservation();

        $user =
            User::factory()->create([
                'is_admin' => false,
            ]);

        $response =
            $this
                ->actingAs($user)
                ->patch(
                    route(
                        'dashboard.reservations.status',
                        $reservationId
                    ),
                    [
                        'status' => 'Confirmée',
                    ]
                );

        $response->assertForbidden();

        $this->assertDatabaseHas(
            'reservations',
            [
                'id' => $reservationId,
                'status' => 'Nouvelle demande',
            ]
        );
    }

    public function test_admin_can_change_reservation_status(): void
    {
        $reservationId =
            $this->createReservation();

        $admin =
            User::factory()->create([
                'is_admin' => true,
            ]);

        $response =
            $this
                ->actingAs($admin)
                ->from('/dashboard')
                ->patch(
                    route(
                        'dashboard.reservations.status',
                        $reservationId
                    ),
                    [
                        'status' => 'Confirmée',
                    ]
                );

        $response->assertRedirect(
            '/dashboard'
        );

        $this->assertDatabaseHas(
            'reservations',
            [
                'id' => $reservationId,
                'status' => 'Confirmée',
            ]
        );
    }

    public function test_admin_cannot_set_an_invalid_status(): void
    {
        $reservationId =
            $this->createReservation();

        $admin =
            User::factory()->create([
                'is_admin' => true,
            ]);

        $response =
            $this
                ->actingAs($admin)
                ->from('/dashboard')
                ->patch(
                    route(
                        'dashboard.reservations.status',
                        $reservationId
                    ),
                    [
                        'status' => 'PAYÉE-PAR-HACKER',
                    ]
                );

        $response
            ->assertRedirect('/dashboard')
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas(
            'reservations',
            [
                'id' => $reservationId,
                'status' => 'Nouvelle demande',
            ]
        );

        $this->assertDatabaseMissing(
            'reservations',
            [
                'id' => $reservationId,
                'status' => 'PAYÉE-PAR-HACKER',
            ]
        );
    }
}