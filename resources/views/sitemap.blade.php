@php
    $staticGroups = [
        [
            'fr' => route('watches.index'),
            'nl' => route('nl.watches.index'),
            'en' => route('en.watches.index'),
            'de' => route('de.watches.index'),
        ],
        [
            'fr' => route('about'),
            'nl' => route('nl.about'),
            'en' => route('en.about'),
            'de' => route('de.about'),
        ],
        [
            'fr' => route('privacy'),
            'nl' => route('nl.privacy'),
            'en' => route('en.privacy'),
            'de' => route('de.privacy'),
        ],
        [
            'fr' => route('reservation-terms'),
            'nl' => route('nl.reservation-terms'),
            'en' => route('en.reservation-terms'),
            'de' => route('de.reservation-terms'),
        ],
        [
            'fr' => route('guides.diamond-vs-moissanite'),
            'nl' => route('nl.guides.diamond-vs-moissanite'),
            'en' => route('en.guides.diamond-vs-moissanite'),
            'de' => route('de.guides.diamond-vs-moissanite'),
        ],
        [
            'fr' => route('guides.vvs-watch'),
            'nl' => route('nl.guides.vvs-watch'),
            'en' => route('en.guides.vvs-watch'),
            'de' => route('de.guides.vvs-watch'),
        ],
        [
            'fr' => route('guides.men-women'),
            'nl' => route('nl.guides.men-women'),
            'en' => route('en.guides.men-women'),
            'de' => route('de.guides.men-women'),
        ],
    ];

    $belgiumGroup = [
        'fr-BE' => route('guides.belgium'),
        'nl-BE' => route('nl.guides.belgium'),
        'en-BE' => route('en.guides.belgium'),
        'de-BE' => route('de.guides.belgium'),
    ];

    $marketUrls = [
        route('guides.france'),
        route('de.guides.germany'),
        route('nl.guides.netherlands'),
    ];
@endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    @foreach ($staticGroups as $group)
        @foreach ($group as $currentUrl)
            <url>
                <loc>{{ $currentUrl }}</loc>
                @foreach ($group as $hreflang => $alternateUrl)
                    <xhtml:link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $alternateUrl }}" />
                @endforeach
                <xhtml:link rel="alternate" hreflang="x-default" href="{{ $group['fr'] }}" />
            </url>
        @endforeach
    @endforeach

    @foreach ($belgiumGroup as $currentUrl)
        <url>
            <loc>{{ $currentUrl }}</loc>
            @foreach ($belgiumGroup as $hreflang => $alternateUrl)
                <xhtml:link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $alternateUrl }}" />
            @endforeach
            <xhtml:link rel="alternate" hreflang="x-default" href="{{ $belgiumGroup['fr-BE'] }}" />
        </url>
    @endforeach

    @foreach ($marketUrls as $marketUrl)
        <url>
            <loc>{{ $marketUrl }}</loc>
        </url>
    @endforeach

    @foreach ($watches as $watch)
        @php
            $watchGroup = [
                'fr' => route('watches.show', $watch),
                'nl' => route('nl.watches.show', $watch),
                'en' => route('en.watches.show', $watch),
                'de' => route('de.watches.show', $watch),
            ];
        @endphp

        @foreach ($watchGroup as $currentUrl)
            <url>
                <loc>{{ $currentUrl }}</loc>
                @foreach ($watchGroup as $hreflang => $alternateUrl)
                    <xhtml:link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $alternateUrl }}" />
                @endforeach
                <xhtml:link rel="alternate" hreflang="x-default" href="{{ $watchGroup['fr'] }}" />

                @if ($watch->updated_at)
                    <lastmod>{{ $watch->updated_at->toAtomString() }}</lastmod>
                @endif
            </url>
        @endforeach
    @endforeach
</urlset>
