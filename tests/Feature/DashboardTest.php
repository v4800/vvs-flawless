<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | VISITEUR
    |--------------------------------------------------------------------------
    */

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response =
            $this->get(
                route('dashboard')
            );

        $response->assertRedirect(
            route('login')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UTILISATEUR CONNECTÉ MAIS NON ADMIN
    |--------------------------------------------------------------------------
    |
    | Être connecté ne suffit PAS.
    |
    */

    public function test_authenticated_non_admin_users_cannot_visit_the_dashboard(): void
    {
        $user =
            User::factory()->create([
                'is_admin' => false,
            ]);

        $response =
            $this
                ->actingAs($user)
                ->get(
                    route('dashboard')
                );

        $response->assertForbidden();
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    public function test_admin_users_can_visit_the_dashboard(): void
    {
        $admin =
            User::factory()->create([
                'is_admin' => true,
            ]);

        $response =
            $this
                ->actingAs($admin)
                ->get(
                    route('dashboard')
                );

        $response->assertOk();
    }
}