<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Watch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReservationDepositTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function createWatch(): Watch
    {
        return Watch::query()->create([
            'name' => 'Montre acompte VVS',
            'price' => 950,
            'japanese_price' => 950,
            'swiss_price' => 1250,
            'description' => 'Montre utilisée pour tester l’acompte.',
            'availability' => 'Sur commande',
            'image' => '/images/watches/test-deposit.webp',
        ]);
    }

    public function test_standard_reservation_snapshots_fixed_one_hundred_euro_deposit(): void
    {
        Mail::fake();

        $watch = $this->createWatch();

        $response = $this
            ->withHeader('X-Inertia', 'true')
            ->post(route('reservations.store'), [
                'watch_id' => $watch->id,
                'movement' => 'Japonais',
                'customer_name' => 'Client Acompte',
                'email' => 'deposit@example.com',
                'phone' => '0470000100',
                'city' => 'Liège',
                'delivery_method' => 'Remise en main propre',
                'confirmation' => true,

                // Valeurs volontairement falsifiées : le serveur les ignore.
                'deposit_amount_snapshot' => 0,
                'deposit_amount' => 0,
                'balance_amount' => 1,
            ]);

        $response->assertStatus(409);

        $reservation = Reservation::query()
            ->where('email', 'deposit@example.com')
            ->firstOrFail();

        $this->assertSame(
            100.0,
            (float) $reservation->deposit_amount_snapshot
        );

        $this->assertSame(
            950.0,
            (float) $reservation->price
        );
    }

    public function test_confirmation_uses_server_snapshot_for_deposit_and_balance(): void
    {
        Mail::fake();

        $watch = $this->createWatch();

        $response = $this
            ->withHeader('X-Inertia', 'true')
            ->post(route('reservations.store'), [
                'watch_id' => $watch->id,
                'movement' => 'Japonais',
                'customer_name' => 'Client Confirmation',
                'email' => 'deposit-confirmation@example.com',
                'phone' => '0470000101',
                'city' => 'Liège',
                'delivery_method' => 'Remise en main propre',
                'confirmation' => true,
            ]);

        $location = $response->headers->get('X-Inertia-Location');

        $this->assertIsString($location);

        $this->withoutHeader('X-Inertia')->get($location)
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where(
                        'reservation.price',
                        fn ($price): bool => (float) $price === 950.0
                    )
                    ->where(
                        'reservation.deposit_amount',
                        fn ($deposit): bool => (float) $deposit === 100.0
                    )
                    ->where(
                        'reservation.balance_amount',
                        fn ($balance): bool => (float) $balance === 850.0
                    )
                    ->etc()
            );
    }
}
