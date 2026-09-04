<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_public_pages_have_security_headers(): void
    {
        $response =
            $this->get(
                route('watches.index')
            );

        $response->assertOk();

        $response->assertHeader(
            'X-Content-Type-Options',
            'nosniff'
        );

        $response->assertHeader(
            'X-Frame-Options',
            'DENY'
        );

        $response->assertHeader(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );

        $response->assertHeader(
            'X-Permitted-Cross-Domain-Policies',
            'none'
        );

        $response->assertHeader(
            'X-XSS-Protection',
            '0'
        );

        $permissions =
            $response->headers->get(
                'Permissions-Policy'
            );

        $this->assertNotNull(
            $permissions
        );

        $this->assertStringContainsString(
            'camera=()',
            $permissions
        );

        $this->assertStringContainsString(
            'microphone=()',
            $permissions
        );

        $this->assertStringContainsString(
            'geolocation=()',
            $permissions
        );

        $this->assertFalse(
            $response->headers->has(
                'X-Powered-By'
            )
        );
    }

    public function test_private_pages_are_not_indexable(): void
    {
        foreach (
            [
                '/login',
                '/forgot-password',
                '/reset-password/test-token',
                '/email/verify',
                '/user/confirm-password',
                '/dashboard',
                '/settings/profile',
                '/reservation-confirmed/test',
                '/nl/reservation-confirmed/test',
            ] as $path
        ) {
            $response = $this->get($path);

            $response->assertHeader(
                'X-Robots-Tag',
                'noindex, nofollow, noarchive'
            );
        }
    }

    public function test_public_watches_page_is_not_marked_noindex(): void
    {
        $response =
            $this->get(
                route('watches.index')
            );

        $response->assertOk();

        $this->assertFalse(
            $response->headers->has(
                'X-Robots-Tag'
            )
        );
    }

    public function test_not_found_pages_are_not_indexable(): void
    {
        $this->get('/page-that-does-not-exist')
            ->assertNotFound()
            ->assertHeader(
                'X-Robots-Tag',
                'noindex, nofollow, noarchive'
            );
    }

    public function test_production_https_has_csp_and_hsts(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SIMULATION PRODUCTION
        |--------------------------------------------------------------------------
        */

        $this->app['env'] =
            'production';

        /*
        |--------------------------------------------------------------------------
        | ROUTE SIMPLE
        |--------------------------------------------------------------------------
        |
        | On utilise une réponse texte pour tester les headers
        | sans dépendre du rendu Vue/Vite.
        |
        */

        Route::get(
            '/security-header-test',
            fn () => response('OK')
        );

        $response =
    $this->get(
        'https://localhost/security-header-test'
    );

        $response->assertOk();

        /*
        |--------------------------------------------------------------------------
        | CSP
        |--------------------------------------------------------------------------
        */

        $csp =
            $response->headers->get(
                'Content-Security-Policy'
            );

        $this->assertNotNull(
            $csp
        );

        $this->assertStringContainsString(
            "default-src 'self'",
            $csp
        );

        $this->assertStringContainsString(
            "object-src 'none'",
            $csp
        );

        $this->assertStringContainsString(
            "frame-ancestors 'none'",
            $csp
        );

        $this->assertStringContainsString(
            "form-action 'self'",
            $csp
        );

        $this->assertStringContainsString(
            'upgrade-insecure-requests',
            $csp
        );

        /*
        |--------------------------------------------------------------------------
        | HSTS
        |--------------------------------------------------------------------------
        */

        $response->assertHeader(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains'
        );
    }

    public function test_production_inline_scripts_use_the_csp_nonce(): void
    {
        $this->app['env'] = 'production';

        $response = $this->get(
            'https://localhost/watches'
        );

        $response->assertOk();

        $contentSecurityPolicy = $response->headers->get(
            'Content-Security-Policy'
        );

        $this->assertNotNull(
            $contentSecurityPolicy
        );

        $this->assertSame(
            1,
            preg_match(
                "/script-src 'self' 'nonce-([^']+)'/",
                $contentSecurityPolicy,
                $matches
            )
        );

        $this->assertGreaterThanOrEqual(
            2,
            substr_count(
                $response->getContent(),
                'nonce="'.$matches[1].'"'
            )
        );
    }
}
