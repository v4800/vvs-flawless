<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $watches = Watch::query()
            ->select([
                'id',
                'updated_at',
            ])
            ->orderBy('id')
            ->get();

        return response()->view(
            'sitemap',
            [
                'watches' => $watches,
            ],
            200,
            [
                'Content-Type' => 'application/xml; charset=UTF-8',
            ]
        );
    }

    public function robots(): Response
    {
        $content = implode(PHP_EOL, [
            'User-agent: *',
            'Allow: /',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response(
            $content,
            200,
            [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]
        );
    }
}
