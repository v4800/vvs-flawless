<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | ROUTES D'INSCRIPTION DÉSACTIVÉES
    |--------------------------------------------------------------------------
    */

    public function test_registration_routes_are_disabled(): void
    {
        $this->assertFalse(
            Route::has('register')
        );

        $this->assertFalse(
            Route::has('register.store')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GET /register
    |--------------------------------------------------------------------------
    */

    public function test_registration_page_cannot_be_accessed(): void
    {
        $this
            ->get('/register')
            ->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | POST /register
    |--------------------------------------------------------------------------
    |
    | Même si quelqu'un essaie d'envoyer directement
    | une requête HTTP, aucun compte ne doit être créé.
    |
    */

    public function test_visitors_cannot_register_by_posting_directly(): void
    {
        $this
            ->post('/register', [
                'name' => 'Attacker',
                'email' => 'attacker@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertNotFound();

        $this->assertDatabaseMissing(
            'users',
            [
                'email' => 'attacker@example.com',
            ]
        );
    }
}
