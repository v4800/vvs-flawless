<?php

namespace Tests\Feature;

use App\Models\Watch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_product_page_only_exposes_published_reviews_for_that_watch(): void
    {
        $watch = Watch::query()->create([
            'name' => 'Montre avis test',
            'price' => 700,
            'description' => 'Montre utilisée pour vérifier les avis.',
            'availability' => 'Sur commande',
        ]);

        $otherWatch = Watch::query()->create([
            'name' => 'Autre montre avis test',
            'price' => 800,
            'description' => 'Autre montre utilisée pour le test.',
            'availability' => 'Sur commande',
        ]);

        DB::table('vvs_customer_reviews')->insert([
            [
                'watch_id' => $watch->id,
                'display_name' => 'Nadia',
                'rating' => 5,
                'body' => 'Très bonne expérience avec cette montre et la remise.',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'watch_id' => $watch->id,
                'display_name' => 'Avis en attente',
                'rating' => 4,
                'body' => 'Cet avis ne doit pas être visible avant la modération.',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'watch_id' => $otherWatch->id,
                'display_name' => 'Autre modèle',
                'rating' => 5,
                'body' => 'Cet avis appartient à un autre modèle de montre.',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->get(route('watches.show', $watch))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Watches/Show')
                ->has('reviews', 1)
                ->where('reviews.0.display_name', 'Nadia')
                ->where('reviews.0.rating', 5)
                ->etc());
    }

    public function test_review_submitted_from_a_watch_is_attached_and_pending(): void
    {
        $watch = Watch::query()->create([
            'name' => 'Montre formulaire avis',
            'price' => 750,
            'description' => 'Montre utilisée pour vérifier le formulaire.',
            'availability' => 'Sur commande',
        ]);

        $watchUrl = route('watches.show', $watch);

        $this->from($watchUrl)
            ->post(route('vvs.reviews.store'), [
                'watch_id' => $watch->id,
                'display_name' => 'Client test',
                'rating' => 4,
                'body' => 'Expérience réelle avec cette montre et remise correcte.',
                'experience' => true,
                'website' => '',
            ])
            ->assertRedirect($watchUrl);

        $this->assertDatabaseHas('vvs_customer_reviews', [
            'watch_id' => $watch->id,
            'display_name' => 'Client test',
            'rating' => 4,
            'status' => 'pending',
        ]);
    }
}