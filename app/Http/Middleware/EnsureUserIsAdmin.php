<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR CONNECTÉ
        |--------------------------------------------------------------------------
        */

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN UNIQUEMENT
        |--------------------------------------------------------------------------
        |
        | Même si quelqu'un réussit à créer ou obtenir un compte utilisateur,
        | il ne peut pas entrer dans le dashboard sans is_admin = true.
        |
        */

        if (
            !$user
            || !(bool) $user->is_admin
        ) {
            abort(403);
        }

        return $next($request);
    }
}