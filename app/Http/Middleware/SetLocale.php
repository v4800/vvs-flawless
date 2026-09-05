<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = match (true) {
            $request->is('nl', 'nl/*') => 'nl_BE',
            $request->is('en', 'en/*') => 'en_BE',
            default => 'fr_BE',
        };

        App::setLocale($locale);

        return $next($request);
    }
}
