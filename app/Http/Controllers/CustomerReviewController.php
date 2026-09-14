<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerReviewController extends Controller
{
    public function index()
    {
        return response()->view('vvs-reviews.index', [
            'reviews' => DB::table('vvs_customer_reviews')->where('status', 'published')
                ->select('id', 'display_name', 'rating', 'body', 'created_at')
                ->orderByDesc('created_at')->orderByDesc('id')->paginate(12),
        ])->header('Cache-Control', 'private, no-store');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'display_name' => ['required', 'string', 'min:2', 'max:50'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:20', 'max:2000'],
            'experience' => ['required', 'accepted'],
            'website' => ['nullable', 'string', 'max:0'],
        ]);
        DB::table('vvs_customer_reviews')->insert([
            'display_name' => trim($data['display_name']),
            'rating' => (int) $data['rating'],
            'body' => trim($data['body']),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('vvs.reviews.index')->with('review_status', 'Merci. Votre avis a été reçu et attend la modération avant publication.');
    }

    public function moderate()
    {
        return response()->view('vvs-reviews.moderate', [
            'reviews' => DB::table('vvs_customer_reviews')->orderByDesc('id')->paginate(25),
        ])->header('Cache-Control', 'private, no-store')->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function update(Request $request, int $review)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['published', 'rejected', 'pending'])],
            'reason' => ['required_if:status,rejected', 'nullable', Rule::in(['spam', 'donnees-personnelles', 'injures-menaces', 'hors-sujet', 'experience-fictive-etablie'])],
        ]);
        abort_unless(DB::table('vvs_customer_reviews')->where('id', $review)->exists(), 404);
        DB::table('vvs_customer_reviews')->where('id', $review)->update([
            'status' => $data['status'],
            'moderation_reason' => $data['status'] === 'rejected' ? $data['reason'] : null,
            'moderated_by' => $request->user()->id,
            'moderated_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('vvs.reviews.moderate')->with('review_status', 'Décision enregistrée.');
    }
}
