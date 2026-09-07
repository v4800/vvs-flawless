<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    @foreach ($staticPages as $page)
        @foreach (['fr' => 'fr-BE', 'nl' => 'nl-BE', 'en' => 'en-BE'] as $language => $hreflang)
            <url>
                <loc>{{ route($page[$language]) }}</loc>
                <xhtml:link rel="alternate" hreflang="fr-BE" href="{{ route($page['fr']) }}" />
                <xhtml:link rel="alternate" hreflang="nl-BE" href="{{ route($page['nl']) }}" />
                <xhtml:link rel="alternate" hreflang="en-BE" href="{{ route($page['en']) }}" />
                <xhtml:link rel="alternate" hreflang="x-default" href="{{ route($page['fr']) }}" />
            </url>
        @endforeach
    @endforeach

    @foreach ($watches as $watch)
        @php
            $watchRoutes = [
                'fr' => route('watches.show', ['watch' => $watch->id]),
                'nl' => route('nl.watches.show', ['watch' => $watch->id]),
                'en' => route('en.watches.show', ['watch' => $watch->id]),
            ];
        @endphp

        @foreach (['fr' => 'fr-BE', 'nl' => 'nl-BE', 'en' => 'en-BE'] as $language => $hreflang)
            <url>
                <loc>{{ $watchRoutes[$language] }}</loc>
                <xhtml:link rel="alternate" hreflang="fr-BE" href="{{ $watchRoutes['fr'] }}" />
                <xhtml:link rel="alternate" hreflang="nl-BE" href="{{ $watchRoutes['nl'] }}" />
                <xhtml:link rel="alternate" hreflang="en-BE" href="{{ $watchRoutes['en'] }}" />
                <xhtml:link rel="alternate" hreflang="x-default" href="{{ $watchRoutes['fr'] }}" />

                @if ($watch->updated_at)
                    <lastmod>{{ $watch->updated_at->toAtomString() }}</lastmod>
                @endif
            </url>
        @endforeach
    @endforeach
</urlset>
