<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CustomerReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_page_does_not_invent_reviews(): void
    {
        $this->get('/avis-clients')->assertOk()->assertSee('Aucun avis publié');
    }

    public function test_new_reviews_are_pending_and_rating_does_not_publish_them(): void
    {
        $this->post('/avis-clients', [
            'display_name' => 'Client test', 'rating' => 1,
            'body' => 'Mon rendez-vous a été retardé et je suis déçu.',
            'experience' => '1', 'website' => '', 'status' => 'published',
        ])->assertRedirect('/avis-clients');
        $this->assertDatabaseHas('vvs_customer_reviews', ['rating' => 1, 'status' => 'pending']);
        $this->get('/avis-clients')->assertDontSee('Mon rendez-vous a été retardé');
    }

    public function test_invalid_reviews_are_rejected(): void
    {
        $this->post('/avis-clients', [
            'display_name' => 'A', 'rating' => 6, 'body' => 'court',
            'website' => 'spam',
        ])->assertSessionHasErrors(['display_name', 'rating', 'body', 'experience', 'website']);
        $this->assertDatabaseCount('vvs_customer_reviews', 0);
    }

    public function test_published_content_is_escaped(): void
    {
        DB::table('vvs_customer_reviews')->insert([
            'display_name' => 'Client', 'rating' => 4, 'body' => '<script>alert(1)</script>',
            'status' => 'published', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->get('/avis-clients')->assertOk()->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_only_admin_can_moderate(): void
    {
        $this->get('/dashboard/avis')->assertRedirect();
        $user = User::factory()->create();
        $user->forceFill(['is_admin' => false])->save();
        $this->actingAs($user)->get('/dashboard/avis')->assertForbidden();
        $this->actingAs($user)->patch('/dashboard/avis/1', ['status' => 'published'])->assertForbidden();
        $user->forceFill(['is_admin' => true])->save();
        $id = DB::table('vvs_customer_reviews')->insertGetId([
            'display_name' => 'Client', 'rating' => 1, 'body' => 'Avis négatif mais respectueux et personnel.',
            'status' => 'pending', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->actingAs($user)->patch('/dashboard/avis/'.$id, ['status' => 'published'])->assertRedirect();
        $this->assertDatabaseHas('vvs_customer_reviews', ['id' => $id, 'status' => 'published', 'rating' => 1]);
        $this->actingAs($user)->patch('/dashboard/avis/'.$id, ['status' => 'rejected'])->assertSessionHasErrors('reason');
    }
}
