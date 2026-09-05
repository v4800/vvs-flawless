<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    <url>
        <loc>{{ route('watches.index') }}</loc>
        <xhtml:link rel="alternate" hreflang="fr-BE" href="{{ route('watches.index') }}" />
        <xhtml:link rel="alternate" hreflang="nl-BE" href="{{ route('nl.watches.index') }}" />
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ route('watches.index') }}" />
    </url>

    <url>
        <loc>{{ route('nl.watches.index') }}</loc>
        <xhtml:link rel="alternate" hreflang="fr-BE" href="{{ route('watches.index') }}" />
        <xhtml:link rel="alternate" hreflang="nl-BE" href="{{ route('nl.watches.index') }}" />
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ route('watches.index') }}" />
    </url>

    <url>
        <loc>{{ route('about') }}</loc>
        <xhtml:link rel="alternate" hreflang="fr-BE" href="{{ route('about') }}" />
        <xhtml:link rel="alternate" hreflang="nl-BE" href="{{ route('nl.about') }}" />
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ route('about') }}" />
    </url>

    <url>
        <loc>{{ route('nl.about') }}</loc>
        <xhtml:link rel="alternate" hreflang="fr-BE" href="{{ route('about') }}" />
        <xhtml:link rel="alternate" hreflang="nl-BE" href="{{ route('nl.about') }}" />
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ route('about') }}" />
    </url>

    <url>
        <loc>{{ route('guides.diamond-vs-moissanite') }}</loc>
        <xhtml:link rel="alternate" hreflang="fr-BE" href="{{ route('guides.diamond-vs-moissanite') }}" />
        <xhtml:link rel="alternate" hreflang="nl-BE" href="{{ route('nl.guides.diamond-vs-moissanite') }}" />
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ route('guides.diamond-vs-moissanite') }}" />
    </url>

    <url>
        <loc>{{ route('nl.guides.diamond-vs-moissanite') }}</loc>
        <xhtml:link rel="alternate" hreflang="fr-BE" href="{{ route('guides.diamond-vs-moissanite') }}" />
        <xhtml:link rel="alternate" hreflang="nl-BE" href="{{ route('nl.guides.diamond-vs-moissanite') }}" />
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ route('guides.diamond-vs-moissanite') }}" />
    </url>

    @foreach ($watches as $watch)
        <url>
            <loc>{{ route('watches.show', ['watch' => $watch->id]) }}</loc>
            <xhtml:link rel="alternate" hreflang="fr-BE" href="{{ route('watches.show', ['watch' => $watch->id]) }}" />
            <xhtml:link rel="alternate" hreflang="nl-BE" href="{{ route('nl.watches.show', ['watch' => $watch->id]) }}" />
            <xhtml:link rel="alternate" hreflang="x-default" href="{{ route('watches.show', ['watch' => $watch->id]) }}" />

            @if ($watch->updated_at)
                <lastmod>{{ $watch->updated_at->toAtomString() }}</lastmod>
            @endif
        </url>

        <url>
            <loc>{{ route('nl.watches.show', ['watch' => $watch->id]) }}</loc>
            <xhtml:link rel="alternate" hreflang="fr-BE" href="{{ route('watches.show', ['watch' => $watch->id]) }}" />
            <xhtml:link rel="alternate" hreflang="nl-BE" href="{{ route('nl.watches.show', ['watch' => $watch->id]) }}" />
            <xhtml:link rel="alternate" hreflang="x-default" href="{{ route('watches.show', ['watch' => $watch->id]) }}" />

            @if ($watch->updated_at)
                <lastmod>{{ $watch->updated_at->toAtomString() }}</lastmod>
            @endif
        </url>
    @endforeach
</urlset>
