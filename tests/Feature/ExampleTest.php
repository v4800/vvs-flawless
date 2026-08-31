<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_home_redirects_to_watches(): void
    {
        $response = $this->get(route('home'));

        $response->assertRedirect(route('watches.index'));
    }

    public function test_watches_page_returns_a_successful_response(): void
    {
        $response = $this->get(route('watches.index'));

        $response->assertOk();
    }
}