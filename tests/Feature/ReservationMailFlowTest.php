<?php

namespace Tests\Feature;

use App\Mail\CustomerReservationMail;
use App\Mail\NewReservationMail;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationMailFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_localized_reservation_stores_customer_locale(): void
    {
        Mail::fake();
        Config::set('vvs.admin_email', null);

        $watchId = DB::table('watches')->insertGetId([
            'name' => 'Localized reservation watch',
            'slug' => 'localized-reservation-watch',
            'price' => 1500,
            'promo_price' => null,
            'description' => 'Localized reservation test watch.',
            'availability' => 'Disponible',
            'image' => '/images/watches/test-locale.webp',
            'japanese_price' => 950,
            'japanese_promo_price' => null,
            'swiss_price' => 1350,
            'swiss_promo_price' => null,
            'stock_quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this
            ->withHeader('X-Inertia', 'true')
            ->post(route('en.reservations.store'), [
                'watch_id' => $watchId,
                'movement' => 'Japonais',
                'customer_name' => 'English Client',
                'email' => 'english-locale@example.com',
                'phone' => '0470000998',
                'city' => 'Liege',
                'delivery_method' => 'Remise en main propre',
                'message' => null,
                'confirmation' => true,
            ])
            ->assertStatus(409);

        $this->assertDatabaseHas('reservations', [
            'email' => 'english-locale@example.com',
            'locale' => 'en_BE',
            'deposit_amount' => 100,
        ]);
    }

    public function test_same_reservation_sends_customer_confirmation_and_admin_notification(): void
    {
        Mail::fake();
        Config::set('vvs.admin_email', 'admin-vvs@example.com');

        $watchId = DB::table('watches')->insertGetId([
            'name' => 'Montre mail VVS',
            'slug' => 'montre-mail-vvs',
            'price' => 1500,
            'promo_price' => null,
            'description' => 'Montre de test du flux mail.',
            'availability' => 'Disponible',
            'image' => '/images/watches/test-mail.webp',
            'japanese_price' => 950,
            'japanese_promo_price' => null,
            'swiss_price' => 1350,
            'swiss_promo_price' => null,
            'stock_quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this
            ->withHeader('X-Inertia', 'true')
            ->post(route('reservations.store'), [
                'watch_id' => $watchId,
                'movement' => 'Japonais',
                'customer_name' => 'Client Mail',
                'email' => 'client-vvs@example.com',
                'phone' => '0470000999',
                'city' => 'Liege',
                'delivery_method' => 'Remise en main propre',
                'message' => 'Test double notification.',
                'confirmation' => true,
            ]);

        $response->assertStatus(409);

        $reservation = Reservation::query()
            ->where('email', 'client-vvs@example.com')
            ->firstOrFail();

        Mail::assertSent(
            CustomerReservationMail::class,
            function (CustomerReservationMail $mail) use ($reservation): bool {
                return $mail->reservation->is($reservation)
                    && $mail->hasTo('client-vvs@example.com')
                    && str_contains($mail->confirmationUrl, 'signature=');
            }
        );

        Mail::assertSent(
            NewReservationMail::class,
            function (NewReservationMail $mail) use ($reservation): bool {
                return $mail->reservation->is($reservation)
                    && $mail->hasTo('admin-vvs@example.com');
            }
        );
    }
}