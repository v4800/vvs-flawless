<?php

namespace Tests\Feature;

use App\Mail\AdminReservationContactMail;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Watch;
use App\Support\ReservationWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminReservationsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_non_admin_cannot_access_admin_reservations(): void
    {
        $this->get(route('admin.reservations.index'))
            ->assertRedirect(route('login'));

        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.reservations.index'))
            ->assertForbidden();
    }

    public function test_admin_page_is_private_and_not_indexable(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.reservations.index'))
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive')
            ->assertHeader(
                'Cache-Control',
                'max-age=0, must-revalidate, no-store, private'
            )
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/Reservations/Index')
            );
    }

    public function test_new_reservation_keeps_a_snapshot_of_watch_identity(): void
    {
        $watch = $this->watch('Montre originale', '/originale.webp');
        $reservation = $this->reservation($watch, [
            'reservation_number' => 'VVS-SNAPSHOT',
        ]);

        $watch->update([
            'name' => 'Montre renommée',
            'image' => '/nouvelle.webp',
        ]);

        $this->assertSame(
            'Montre originale',
            $reservation->refresh()->watch_name_snapshot
        );
        $this->assertSame(
            '/originale.webp',
            $reservation->watch_image_snapshot
        );

        $this->actingAs($this->admin())
            ->get(route('admin.reservations.index'))
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where(
                        'reservations.data.0.watch_name_snapshot',
                        'Montre originale'
                    )
                    ->where(
                        'reservations.data.0.watch_image_snapshot',
                        '/originale.webp'
                    )
            );
    }

    public function test_dashboard_uses_server_side_pagination_and_search(): void
    {
        $watch = $this->watch();

        foreach (range(1, 21) as $index) {
            $this->reservation($watch, [
                'customer_name' => $index === 21
                    ? 'Client Recherche Unique'
                    : 'Client '.$index,
                'reservation_number' => 'VVS-PAGE-'.$index,
            ]);
        }

        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.reservations.index'))
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('reservations.per_page', 20)
                    ->where('reservations.total', 21)
                    ->has('reservations.data', 20)
            );

        $this->actingAs($admin)
            ->get(route('admin.reservations.index', [
                'q' => 'Recherche Unique',
            ]))
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('reservations.total', 1)
                    ->where(
                        'reservations.data.0.customer_name',
                        'Client Recherche Unique'
                    )
            );
    }

    public function test_price_is_split_into_expected_25_and_75_percent_amounts(): void
    {
        $reservation = $this->reservation($this->watch(), [
            'price' => 1000,
            'reservation_number' => 'VVS-PRICE-SPLIT',
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.reservations.index', [
                'q' => $reservation->reservation_number,
            ]))
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('reservations.data.0.deposit_amount', 250)
                    ->where('reservations.data.0.balance_amount', 750)
                    ->where(
                        'reservations.data.0.confirmed_paid_amount',
                        0
                    )
            );
    }

    public function test_admin_can_update_workflow_and_history_is_recorded(): void
    {
        $admin = $this->admin();
        $reservation = $this->reservation($this->watch(), [
            'reservation_number' => 'VVS-WORKFLOW',
        ]);

        $this->actingAs($admin)
            ->patch(
                route('admin.reservations.update', $reservation),
                [
                    'status' => ReservationWorkflow::DEPOSIT_PAID,
                    'deposit_paid_at' => '2026-09-14 18:00:00',
                    'appointment_at' => '2026-09-16 14:30:00',
                    'handover_address' => 'Liège, Belgique',
                    'travel_fee' => 25,
                    'admin_notes' => 'Adresse confirmée avec le client.',
                    'is_test' => false,
                ]
            )
            ->assertRedirect();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => ReservationWorkflow::DEPOSIT_PAID,
            'handover_address' => 'Liège, Belgique',
            'travel_fee' => 25,
        ]);

        $this->assertDatabaseHas('reservation_status_histories', [
            'reservation_id' => $reservation->id,
            'changed_by' => $admin->id,
            'old_status' => ReservationWorkflow::NEW,
            'new_status' => ReservationWorkflow::DEPOSIT_PAID,
        ]);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $reservation = $this->reservation($this->watch());

        $this->actingAs($this->admin())
            ->patch(
                route('admin.reservations.update', $reservation),
                ['status' => 'PAYÉE-PAR-HACKER']
            )
            ->assertSessionHasErrors('status');

        $this->assertSame(
            ReservationWorkflow::NEW,
            $reservation->refresh()->status
        );
    }

    public function test_archived_and_test_reservations_are_hidden_by_default(): void
    {
        $watch = $this->watch();
        $normal = $this->reservation($watch, [
            'reservation_number' => 'VVS-NORMAL',
        ]);
        $this->reservation($watch, [
            'reservation_number' => 'VVS-TEST',
            'is_test' => true,
        ]);
        $archived = $this->reservation($watch, [
            'reservation_number' => 'VVS-ARCHIVED',
            'archived_at' => now(),
        ]);

        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.reservations.index'))
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('reservations.total', 1)
                    ->where(
                        'reservations.data.0.id',
                        $normal->id
                    )
            );

        $this->actingAs($admin)
            ->get(route('admin.reservations.index', [
                'show_archived' => 1,
                'show_test' => 1,
            ]))
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where('reservations.total', 3)
            );

        $this->actingAs($admin)
            ->patch(
                route('admin.reservations.archive', $archived),
                ['archived' => false]
            )
            ->assertRedirect();

        $this->assertNull($archived->refresh()->archived_at);
    }

    public function test_admin_can_send_an_email_from_the_dashboard(): void
    {
        Mail::fake();

        $reservation = $this->reservation($this->watch(), [
            'email' => 'contact-client@example.com',
            'reservation_number' => 'VVS-EMAIL-TEST',
        ]);

        $this->actingAs($this->admin())
            ->post(route('admin.reservations.email', $reservation), [
                'subject' => 'Votre réservation VVS-EMAIL-TEST',
                'message' => 'Votre montre est prête.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        Mail::assertSent(
            AdminReservationContactMail::class,
            fn (AdminReservationContactMail $mail): bool => $mail
                ->hasTo('contact-client@example.com')
                && $mail->subjectLine === 'Votre réservation VVS-EMAIL-TEST'
                && $mail->messageBody === 'Votre montre est prête.'
        );
    }

    public function test_non_admin_cannot_send_a_reservation_email(): void
    {
        Mail::fake();

        $reservation = $this->reservation($this->watch());

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->post(route('admin.reservations.email', $reservation), [
                'subject' => 'Tentative',
                'message' => 'Message interdit.',
            ])
            ->assertForbidden();

        Mail::assertNothingSent();
    }

    public function test_review_invitation_is_only_enabled_after_completion(): void
    {
        $watch = $this->watch();
        $this->reservation($watch, [
            'reservation_number' => 'VVS-OPEN',
        ]);
        $this->reservation($watch, [
            'reservation_number' => 'VVS-DONE',
            'status' => ReservationWorkflow::COMPLETED,
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.reservations.index', [
                'sort' => 'oldest',
            ]))
            ->assertInertia(
                fn (Assert $page) => $page
                    ->where(
                        'reservations.data.0.review_invitation_allowed',
                        false
                    )
                    ->where(
                        'reservations.data.1.review_invitation_allowed',
                        true
                    )
            );
    }

    private function admin(): User
    {
        return User::factory()->create([
            'is_admin' => true,
        ]);
    }

    private function watch(
        string $name = 'Montre Dashboard',
        string $image = '/images/dashboard.webp'
    ): Watch {
        return Watch::query()->create([
            'name' => $name,
            'price' => 1200,
            'promo_price' => 950,
            'japanese_price' => 1200,
            'japanese_promo_price' => 950,
            'swiss_price' => 1600,
            'swiss_promo_price' => 1350,
            'description' => 'Montre utilisée pour les tests du dashboard.',
            'availability' => 'Disponible',
            'stock_quantity' => 1,
            'image' => $image,
        ]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function reservation(
        Watch $watch,
        array $attributes = []
    ): Reservation {
        return Reservation::query()->create([
            'watch_id' => $watch->id,
            'movement' => 'Japonais',
            'price' => 950,
            'customer_name' => 'Client Dashboard',
            'email' => 'client@example.com',
            'phone' => '0470000000',
            'city' => 'Liège',
            'delivery_method' => 'Remise en main propre',
            'status' => ReservationWorkflow::NEW,
            'reservation_number' => 'VVS-'.strtoupper(str()->random(12)),
            'message' => null,
            ...$attributes,
        ]);
    }
}
