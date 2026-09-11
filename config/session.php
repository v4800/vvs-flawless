<?php

use Illuminate\Support\Str;

$isProduction = env('APP_ENV') === 'production';

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    // In production the admin authentication cookie should disappear when
    // the browser closes instead of remaining persisted unnecessarily.
    'expire_on_close' => $isProduction
        ? true
        : env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    */

    // Encrypt database-backed session payloads in production. This is an
    // additional layer of protection if the session table is ever exposed.
    'encrypt' => $isProduction
        ? true
        : env('SESSION_ENCRYPT', false),

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel')).'-session',
    ),

    'path' => env('SESSION_PATH', '/'),

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies
    |--------------------------------------------------------------------------
    */

    // Production is HTTPS-only. Forcing Secure here prevents an accidental
    // environment variable from sending an authentication cookie over HTTP.
    'secure' => $isProduction
        ? true
        : env('SESSION_SECURE_COOKIE'),

    'http_only' => env('SESSION_HTTP_ONLY', true),

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

    // JSON avoids PHP object deserialization gadget chains in session data.
    'serialization' => 'json',

];
