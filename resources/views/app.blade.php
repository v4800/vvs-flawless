<!DOCTYPE html>
<html
    lang="{{ data_get($page, 'props.seo.language') ?: str_replace('_', '-', app()->getLocale()) }}"
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

            $localizedSeoFallback = \Illuminate\Support\Facades\Lang::get(
                'site.seo.collection_description',
                [],
                app()->getLocale(),
                false
            );

            $seoDescription = is_string($seo['description'] ?? null)
                ? $seo['description']
                : (is_string($localizedSeoFallback)
                    ? $localizedSeoFallback
                    : 'VVS FLAWLESS');

            $seoCanonical = is_string($seo['canonical'] ?? null)
                ? $seo['canonical']
                : request()->url();

            $seoAlternates = is_array($seo['alternates'] ?? null)
                ? $seo['alternates']
                : [];

            $seoLocale = is_string($seo['locale'] ?? null)
                ? $seo['locale']
                : app()->getLocale();

            $openGraphLocale = str_replace('-', '_', $seoLocale);

            $seoImage = is_string($seo['image'] ?? null)
                ? $seo['image']
                : url('/images/vvs-flawless-profile.webp');

            $seoImageAlt = is_string($seo['imageAlt'] ?? null)
                ? $seo['imageAlt']
                : 'VVS FLAWLESS';

            $seoType = is_string($seo['type'] ?? null)
                ? $seo['type']
                : 'website';

            $seoRobots = is_string($seo['robots'] ?? null)
                ? $seo['robots']
                : 'index,follow';

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
            name="robots"
            content="{{ $seoRobots }}"
        >

        <meta
            name="theme-color"
            content="#000000"
        >

        <link
            rel="canonical"
            href="{{ $seoCanonical }}"
        >

        @foreach ($seoAlternates as $alternate)
            @if (
                is_array($alternate)
                && is_string($alternate['hreflang'] ?? null)
                && is_string($alternate['href'] ?? null)
            )
                <link
                    rel="alternate"
                    hreflang="{{ $alternate['hreflang'] }}"
                    href="{{ $alternate['href'] }}"
                >
            @endif
        @endforeach

        {{-- Open Graph --}}

        <meta
            property="og:locale"
            content="{{ $openGraphLocale }}"
        >

        @foreach ($seoAlternates as $alternate)
            @if (
                is_array($alternate)
                && ($alternate['hreflang'] ?? null) !== 'x-default'
                && str_replace('-', '_', (string) ($alternate['hreflang'] ?? '')) !== $openGraphLocale
            )
                <meta
                    property="og:locale:alternate"
                    content="{{ str_replace('-', '_', $alternate['hreflang']) }}"
                >
            @endif
        @endforeach

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

        <meta
            property="og:image:alt"
            content="{{ $seoImageAlt }}"
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

        <meta
            name="twitter:image:alt"
            content="{{ $seoImageAlt }}"
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
        {{-- VVS LUCKY FALCON LOADER --}}
        <div
            id="vvs-route-loader"
            class="vvs-route-loader is-active"
            aria-hidden="true"
        >
            <div class="vvs-lucky-loader">
                <span
                    class="vvs-lucky-loader__ring vvs-lucky-loader__ring--outer"
                ></span>

                <span
                    class="vvs-lucky-loader__ring vvs-lucky-loader__ring--inner"
                ></span>

                <span class="vvs-lucky-loader__core"></span>
            </div>
        </div>

        <script
            @if ($cspNonce)
                nonce="{{ $cspNonce }}"
            @endif
        >
            (() => {
                const loader =
                    document.getElementById('vvs-route-loader');

                if (!loader) {
                    return;
                }

                let timer = null;

                const showLoader = () => {
                    if (timer) {
                        window.clearTimeout(timer);
                    }

                    loader.classList.add('is-active');
                };

                const hideLoader = () => {
                    if (timer) {
                        window.clearTimeout(timer);
                    }

                    timer = window.setTimeout(() => {
                        loader.classList.remove('is-active');
                    }, 90);
                };

                document.addEventListener(
                    'inertia:start',
                    showLoader,
                );

                document.addEventListener(
                    'inertia:finish',
                    hideLoader,
                );

                document.addEventListener(
                    'inertia:invalid',
                    hideLoader,
                );

                document.addEventListener(
                    'inertia:exception',
                    hideLoader,
                );

                window.addEventListener(
                    'load',
                    hideLoader,
                    { once: true },
                );

                window.addEventListener(
                    'pageshow',
                    hideLoader,
                );
            })();
        </script>
        <x-inertia::app />
    </body>
</html>
