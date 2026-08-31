<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ConfirmationSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function createReservation(): string
    {
        $watchId = DB::table('watches')->insertGetId([
            'name' => 'Montre Confirmation Test',
            'price' => 1500.00,
            'promo_price' => 950.00,
            'description' => 'Test confirmation',
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

        $reservationNumber = 'VVS-SECURITY123';

        DB::table('reservations')->insert([
            'watch_id' => $watchId,
            'movement' => 'Japonais',
            'price' => 950.00,

            'customer_name' => 'Client Test',
            'email' => 'confirmation@example.com',
            'phone' => '0470000000',
            'city' => 'Liège',

            'delivery_method' => 'Remise en main propre - point de rencontre',

            'status' => 'Nouvelle demande',

            'reservation_number' => $reservationNumber,

            'message' => null,

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $reservationNumber;
    }

    public function test_confirmation_page_cannot_be_accessed_without_signature(): void
    {
        $reservationNumber =
            $this->createReservation();

        $response =
            $this->get(
                "/reservation-confirmed/{$reservationNumber}"
            );

        $response->assertForbidden();
    }

    public function test_valid_signed_confirmation_url_can_be_accessed(): void
    {
        $reservationNumber =
            $this->createReservation();

        $signedUrl =
            URL::temporarySignedRoute(
                'reservations.confirmation',
                now()->addMinutes(10),
                [
                    'reservationNumber' => $reservationNumber,
                ]
            );

        $response =
            $this->get($signedUrl);

        $response->assertOk();

        $cacheControl =
    $response->headers->get(
        'Cache-Control'
    );

        $this->assertNotNull(
            $cacheControl
        );

        $this->assertStringContainsString(
            'no-store',
            $cacheControl
        );

        $this->assertStringContainsString(
            'private',
            $cacheControl
        );

        $this->assertStringContainsString(
            'max-age=0',
            $cacheControl
        );

        $this->assertStringContainsString(
            'must-revalidate',
            $cacheControl
        );

        $response->assertHeader(
            'Pragma',
            'no-cache'
        );

        $response->assertHeader(
            'Expires',
            '0'
        );
    }

    public function test_expired_signed_confirmation_url_is_rejected(): void
    {
        $reservationNumber =
            $this->createReservation();

        $expiredUrl =
            URL::temporarySignedRoute(
                'reservations.confirmation',
                now()->subMinute(),
                [
                    'reservationNumber' => $reservationNumber,
                ]
            );

        $response =
            $this->get($expiredUrl);

        $response->assertForbidden();
    }
}
