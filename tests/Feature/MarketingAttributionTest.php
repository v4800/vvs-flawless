<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MarketingAttributionTest extends TestCase
{
    use RefreshDatabase;

    private function createWatch(): int
    {
        return DB::table('watches')
            ->insertGetId([
                'name' => 'Montre Marketing Test',
                'price' => 1500.00,
                'promo_price' => 1000.00,
                'description' => 'Montre utilisée pour tester l’attribution marketing.',
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

    /**
     * @return array<string, mixed>
     */
    private function reservationPayload(
        int $watchId
    ): array {
        return [
            'watch_id' => $watchId,
            'movement' => 'Japonais',
            'customer_name' => 'Client Marketing',
            'email' => 'marketing@example.com',
            'phone' => '0470000010',
            'city' => 'Liège',
            'message' => 'Test attribution TikTok',
            'confirmation' => true,
        ];
    }

    public function test_home_redirect_preserves_utm_parameters(): void
    {
        $parameters = [
            'utm_source' => 'tiktok',
            'utm_medium' => 'organic_social',
            'utm_campaign' => 'ap',
        ];

        $response = $this->get(
            '/?'.http_build_query($parameters)
        );

        $response->assertRedirect(
            route(
                'watches.index',
                $parameters
            )
        );
    }

    public function test_watch_page_captures_utm_attribution_in_session(): void
    {
        $response = $this->get(
            route(
                'watches.index',
                [
                    'utm_source' => 'tiktok',
                    'utm_medium' => 'organic_social',
                    'utm_campaign' => 'ap',
                ]
            )
        );

        $response->assertOk();

        $response->assertSessionHas(
            'marketing_attribution',
            [
                'utm_source' => 'tiktok',
                'utm_medium' => 'organic_social',
                'utm_campaign' => 'ap',
            ]
        );
    }

    public function test_normal_navigation_does_not_delete_existing_attribution(): void
    {
        $watchId = $this->createWatch();

        $response = $this
            ->withSession([
                'marketing_attribution' => [
                    'utm_source' => 'tiktok',
                    'utm_medium' => 'organic_social',
                    'utm_campaign' => 'ap',
                ],
            ])
            ->get(
                route(
                    'watches.show',
                    $watchId
                )
            );

        $response->assertOk();

        $response->assertSessionHas(
            'marketing_attribution',
            [
                'utm_source' => 'tiktok',
                'utm_medium' => 'organic_social',
                'utm_campaign' => 'ap',
            ]
        );
    }

    public function test_reservation_saves_attribution_from_session(): void
    {
        Mail::fake();

        $watchId = $this->createWatch();

        $response = $this
            ->withSession([
                'marketing_attribution' => [
                    'utm_source' => 'tiktok',
                    'utm_medium' => 'organic_social',
                    'utm_campaign' => 'ap',
                ],
            ])
            ->withHeader(
                'X-Inertia',
                'true'
            )
            ->post(
                route(
                    'reservations.store'
                ),
                $this->reservationPayload(
                    $watchId
                )
            );

        $response->assertStatus(409);

        $this->assertDatabaseHas(
            'reservations',
            [
                'email' => 'marketing@example.com',
                'utm_source' => 'tiktok',
                'utm_medium' => 'organic_social',
                'utm_campaign' => 'ap',
            ]
        );
    }

    public function test_new_tracked_visit_replaces_previous_attribution(): void
    {
        $response = $this
            ->withSession([
                'marketing_attribution' => [
                    'utm_source' => 'instagram',
                    'utm_medium' => 'organic_social',
                    'utm_campaign' => 'ancienne',
                ],
            ])
            ->get(
                route(
                    'watches.index',
                    [
                        'utm_source' => 'tiktok',
                        'utm_medium' => 'organic_social',
                        'utm_campaign' => 'nouvelle',
                    ]
                )
            );

        $response->assertOk();

        $response->assertSessionHas(
            'marketing_attribution',
            [
                'utm_source' => 'tiktok',
                'utm_medium' => 'organic_social',
                'utm_campaign' => 'nouvelle',
            ]
        );
    }

    public function test_client_cannot_fake_attribution_in_reservation_post(): void
    {
        Mail::fake();

        $watchId = $this->createWatch();

        $payload = $this->reservationPayload(
            $watchId
        );

        $payload['utm_source'] = 'fake-source';
        $payload['utm_medium'] = 'fake-medium';
        $payload['utm_campaign'] = 'fake-campaign';

        $response = $this
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

        $this->assertDatabaseHas(
            'reservations',
            [
                'email' => 'marketing@example.com',
                'utm_source' => null,
                'utm_medium' => null,
                'utm_campaign' => null,
            ]
        );
    }
}
