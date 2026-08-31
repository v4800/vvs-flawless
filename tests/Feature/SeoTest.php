<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_is_public_and_contains_collection(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();

        $response->assertHeader(
            'Content-Type',
            'application/xml; charset=UTF-8'
        );

        $response->assertSee(
            '/watches',
            false
        );

        $response->assertDontSee(
            '/dashboard',
            false
        );
    }

    public function test_robots_exposes_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();

        $response->assertHeader(
            'Content-Type',
            'text/plain; charset=UTF-8'
        );

        $response->assertSee(
            'User-agent: *'
        );

        $response->assertSee(
            'Allow: /'
        );

        $response->assertSee(
            '/sitemap.xml'
        );
    }
}
