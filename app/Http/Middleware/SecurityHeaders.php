<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | MASQUER LA TECHNOLOGIE PHP
        |--------------------------------------------------------------------------
        |
        | PHP peut envoyer automatiquement :
        |
        | X-Powered-By: PHP/x.x.x
        |
        | On retire cette information.
        |
        */

        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }

        /*
        |--------------------------------------------------------------------------
        | CSP NONCE
        |--------------------------------------------------------------------------
        */

        if (app()->isProduction()) {
            Vite::useCspNonce();
        }

        $response = $next($request);

        /*
        |--------------------------------------------------------------------------
        | SUPPRESSION DES HEADERS TECHNOLOGIQUES
        |--------------------------------------------------------------------------
        */

        $response->headers->remove(
            'X-Powered-By'
        );

        /*
        |--------------------------------------------------------------------------
        | MIME SNIFFING
        |--------------------------------------------------------------------------
        */

        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        /*
        |--------------------------------------------------------------------------
        | CLICKJACKING
        |--------------------------------------------------------------------------
        */

        $response->headers->set(
            'X-Frame-Options',
            'DENY'
        );

        /*
        |--------------------------------------------------------------------------
        | REFERRER POLICY
        |--------------------------------------------------------------------------
        */

        $response->headers->set(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );

        /*
        |--------------------------------------------------------------------------
        | PERMISSIONS POLICY
        |--------------------------------------------------------------------------
        */

        $response->headers->set(
            'Permissions-Policy',
            implode(', ', [
                'camera=()',
                'microphone=()',
                'geolocation=()',
                'payment=()',
                'usb=()',
                'accelerometer=()',
                'gyroscope=()',
                'magnetometer=()',
            ])
        );

        /*
        |--------------------------------------------------------------------------
        | CROSS DOMAIN POLICIES
        |--------------------------------------------------------------------------
        */

        $response->headers->set(
            'X-Permitted-Cross-Domain-Policies',
            'none'
        );

        /*
        |--------------------------------------------------------------------------
        | ANCIEN XSS AUDITOR
        |--------------------------------------------------------------------------
        */

        $response->headers->set(
            'X-XSS-Protection',
            '0'
        );

        /*
        |--------------------------------------------------------------------------
        | PAGES PRIVÉES : PAS D'INDEXATION
        |--------------------------------------------------------------------------
        |
        | Google/Bing ne doivent pas indexer :
        |
        | - dashboard
        | - login / auth
        | - confirmation client
        | - réglages
        |
        */

        if (
            $request->is('dashboard*')
            || $request->is('login*')
            || $request->is('two-factor-challenge*')
            || $request->is('reservation-confirmed/*')
            || $request->is('settings*')
        ) {
            $response->headers->set(
                'X-Robots-Tag',
                'noindex, nofollow, noarchive'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CONTENT SECURITY POLICY
        |--------------------------------------------------------------------------
        |
        | Production uniquement afin de ne pas casser Vite HMR en local.
        |
        */

        if (app()->isProduction()) {

            $nonce = Vite::cspNonce();

            $contentSecurityPolicy =
                implode(
                    '; ',
                    [
                        "default-src 'self'",

                        "base-uri 'self'",

                        "object-src 'none'",

                        "frame-ancestors 'none'",

                        "form-action 'self'",

                        "script-src 'self' 'nonce-{$nonce}'",

                        "script-src-attr 'none'",

                        "style-src 'self' 'unsafe-inline'",

                        "img-src 'self' data: blob:",

                        "font-src 'self' data:",

                        "connect-src 'self'",

                        "media-src 'self'",

                        "worker-src 'self' blob:",

                        "manifest-src 'self'",

                        "frame-src 'none'",

                        'upgrade-insecure-requests',
                    ]
                );

            $response->headers->set(
                'Content-Security-Policy',
                $contentSecurityPolicy
            );

            /*
            |--------------------------------------------------------------------------
            | HSTS
            |--------------------------------------------------------------------------
            |
            | Seulement sur une vraie connexion HTTPS.
            |
            */

            if ($request->isSecure()) {
                $response->headers->set(
                    'Strict-Transport-Security',
                    'max-age=31536000; includeSubDomains'
                );
            }
        }

        return $response;
    }
}