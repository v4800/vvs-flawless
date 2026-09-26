<?php

namespace Tests\Feature;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MovementAvailabilitySecurityV3Test extends TestCase
{
    use RefreshDatabase;

    private function createWatch(array $overrides = []): int
    {
        return DB::table('watches')->insertGetId(array_merge([
            'name' => 'Movement availability security V3',
            'price' => 1500.00,
            'promo_price' => null,
            'description' => 'Security regression test.',
            'availability' => 'Disponible',
            'image' => '/images/watches/security-v3.webp',
            'japanese_price' => 1390.00,
            'japanese_promo_price' => 950.00,
            'swiss_price' => 1950.00,
            'swiss_promo_price' => 1350.00,
            'stock_quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(int $watchId, string $movement, string $email): array
    {
        return [
            'watch_id' => $watchId,
            'movement' => $movement,
            'customer_name' => 'Security Test',
            'email' => $email,
            'phone' => '0470000000',
            'city' => 'Liège',
            'delivery_method' => 'Remise en main propre',
            'message' => null,
            'confirmation' => true,
        ];
    }

    public function test_swiss_promo_alone_cannot_create_swiss_availability(): void
    {
        Mail::fake();

        $watchId = $this->createWatch([
            'swiss_price' => null,
            'swiss_promo_price' => 900.00,
        ]);

        $response = $this->post(
            route('reservations.store'),
            $this->payload($watchId, 'Suisse', 'promo-only-swiss-v3@example.com')
        );

        $response->assertStatus(422);

        $this->assertDatabaseMissing('reservations', [
            'email' => 'promo-only-swiss-v3@example.com',
        ]);
    }

    public function test_japanese_promo_alone_cannot_create_japanese_availability(): void
    {
        Mail::fake();

        $watchId = $this->createWatch([
            'japanese_price' => null,
            'japanese_promo_price' => 700.00,
        ]);

        $response = $this->post(
            route('reservations.store'),
            $this->payload($watchId, 'Japonais', 'promo-only-japanese-v3@example.com')
        );

        $response->assertStatus(422);

        $this->assertDatabaseMissing('reservations', [
            'email' => 'promo-only-japanese-v3@example.com',
        ]);
    }

    public function test_valid_promo_is_used_when_base_movement_exists(): void
    {
        Mail::fake();

        $watchId = $this->createWatch([
            'swiss_price' => 1950.00,
            'swiss_promo_price' => 1350.00,
        ]);

        $response = $this
            ->withHeader('X-Inertia', 'true')
            ->post(
                route('reservations.store'),
                $this->payload($watchId, 'Suisse', 'valid-swiss-v3@example.com')
            );

        $response->assertStatus(409);

        $reservation = Reservation::query()
            ->where('email', 'valid-swiss-v3@example.com')
            ->firstOrFail();

        $this->assertSame(1350.0, (float) $reservation->price);
    }
}