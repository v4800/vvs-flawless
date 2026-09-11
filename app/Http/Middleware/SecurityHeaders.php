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
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }

        if (app()->isProduction()) {
            Vite::useCspNonce();
        }

        $response = $next($request);

        $response->headers->remove('X-Powered-By');

        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        $response->headers->set(
            'X-Frame-Options',
            'DENY'
        );

        $response->headers->set(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );

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

        $response->headers->set(
            'X-Permitted-Cross-Domain-Policies',
            'none'
        );

        // The legacy browser XSS auditor is disabled on purpose. The CSP below
        // and framework output escaping are the primary XSS defenses.
        $response->headers->set(
            'X-XSS-Protection',
            '0'
        );

        // Isolate the application from unrelated browsing contexts without
        // requiring COEP, which could break legitimate external resources.
        $response->headers->set(
            'Cross-Origin-Opener-Policy',
            'same-origin'
        );

        $response->headers->set(
            'Cross-Origin-Resource-Policy',
            'same-origin'
        );

        $isPrivatePage =
            $request->is('dashboard*')
            || $request->is('login*')
            || $request->is('forgot-password*')
            || $request->is('reset-password*')
            || $request->is('email/verify*')
            || $request->is('user/confirm-password*')
            || $request->is('register*')
            || $request->is('two-factor-challenge*')
            || $request->is('reservation-confirmed/*')
            || $request->is('nl/reservation-confirmed/*')
            || $request->is('en/reservation-confirmed/*')
            || $request->is('settings*');

        if (
            $response->getStatusCode() === 404
            || $isPrivatePage
        ) {
            $response->headers->set(
                'X-Robots-Tag',
                'noindex, nofollow, noarchive'
            );
        }

        if ($isPrivatePage) {
            $response->headers->set(
                'Cache-Control',
                'no-store, private, max-age=0, must-revalidate'
            );

            $response->headers->set(
                'Pragma',
                'no-cache'
            );

            $response->headers->set(
                'Expires',
                '0'
            );
        }

        if (app()->isProduction()) {
            $nonce = Vite::cspNonce();

            $contentSecurityPolicy = implode(
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
