<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    @class(['dark' => ($appearance ?? 'system') == 'dark'])
>
    <head>
        @php
            $seo = is_array($page['props']['seo'] ?? null)
                ? $page['props']['seo']
                : [];

            $seoTitle = is_string($seo['title'] ?? null)
                ? $seo['title']
                : config('app.name', 'VVS FLAWLESS');

            $seoDescription = is_string($seo['description'] ?? null)
                ? $seo['description']
                : 'VVS FLAWLESS — Montres iced-out en moissanite VVS couleur D en Belgique.';

            $seoCanonical = is_string($seo['canonical'] ?? null)
                ? $seo['canonical']
                : request()->url();

            $seoImage = is_string($seo['image'] ?? null)
                ? $seo['image']
                : url('/images/vvs-flawless-profile.webp');

            $seoType = is_string($seo['type'] ?? null)
                ? $seo['type']
                : 'website';

            $structuredData = is_array($seo['structuredData'] ?? null)
                ? $seo['structuredData']
                : null;

            $cspNonce = app()->isProduction()
                ? \Illuminate\Support\Facades\Vite::cspNonce()
                : null;
        @endphp

        <meta charset="utf-8">

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >

        <meta
            name="description"
            content="{{ $seoDescription }}"
        >

        <meta
            name="theme-color"
            content="#000000"
        >

        <link
            rel="canonical"
            href="{{ $seoCanonical }}"
        >

        {{-- Open Graph --}}

        <meta
            property="og:locale"
            content="fr_BE"
        >

        <meta
            property="og:site_name"
            content="VVS FLAWLESS"
        >

        <meta
            property="og:type"
            content="{{ $seoType }}"
        >

        <meta
            property="og:title"
            content="{{ $seoTitle }}"
        >

        <meta
            property="og:description"
            content="{{ $seoDescription }}"
        >

        <meta
            property="og:url"
            content="{{ $seoCanonical }}"
        >

        <meta
            property="og:image"
            content="{{ $seoImage }}"
        >

        {{-- Partage social --}}

        <meta
            name="twitter:card"
            content="summary_large_image"
        >

        <meta
            name="twitter:title"
            content="{{ $seoTitle }}"
        >

        <meta
            name="twitter:description"
            content="{{ $seoDescription }}"
        >

        <meta
            name="twitter:image"
            content="{{ $seoImage }}"
        >

        {{-- Données structurées SEO --}}

        @if ($structuredData)
            <script
                @if ($cspNonce)
                    nonce="{{ $cspNonce }}"
                @endif
                type="application/ld+json"
            >{!! json_encode(
                $structuredData,
                JSON_UNESCAPED_SLASHES
                | JSON_UNESCAPED_UNICODE
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS
                | JSON_HEX_QUOT
            ) !!}</script>
        @endif

        {{-- Détection immédiate du thème --}}

        <script
            @if ($cspNonce)
                nonce="{{ $cspNonce }}"
            @endif
        >
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia(
                        '(prefers-color-scheme: dark)',
                    ).matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <link
            rel="icon"
            href="/favicon.ico"
            sizes="any"
        >

        <link
            rel="icon"
            href="/favicon.svg"
            type="image/svg+xml"
        >

        <link
            rel="apple-touch-icon"
            href="/apple-touch-icon.png"
        >

        @fonts

        @vite([
            'resources/css/app.css',
            'resources/js/app.ts',
            "resources/js/pages/{$page['component']}.vue",
        ])

        <x-inertia::head>
            <title>{{ $seoTitle }}</title>
        </x-inertia::head>
    </head>

    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
