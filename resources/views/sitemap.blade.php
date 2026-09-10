@php
    $staticGroups = [
        [
            'fr-BE' => route('watches.index'),
            'nl-BE' => route('nl.watches.index'),
            'en-BE' => route('en.watches.index'),
        ],
        [
            'fr-BE' => route('about'),
            'nl-BE' => route('nl.about'),
            'en-BE' => route('en.about'),
        ],
        [
            'fr-BE' => route('guides.diamond-vs-moissanite'),
            'nl-BE' => route('nl.guides.diamond-vs-moissanite'),
            'en-BE' => route('en.guides.diamond-vs-moissanite'),
        ],
        [
            'fr-BE' => route('guides.vvs-watch'),
            'nl-BE' => route('nl.guides.vvs-watch'),
            'en-BE' => route('en.guides.vvs-watch'),
        ],
        [
            'fr-BE' => route('guides.men-women'),
            'nl-BE' => route('nl.guides.men-women'),
            'en-BE' => route('en.guides.men-women'),
        ],
        [
            'fr-BE' => route('guides.belgium'),
            'nl-BE' => route('nl.guides.belgium'),
            'en-BE' => route('en.guides.belgium'),
        ],
    ];
@endphp
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    @foreach ($staticGroups as $group)
        @foreach ($group as $currentUrl)
            <url>
                <loc>{{ $currentUrl }}</loc>
                @foreach ($group as $hreflang => $alternateUrl)
                    <xhtml:link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $alternateUrl }}" />
                @endforeach
                <xhtml:link rel="alternate" hreflang="x-default" href="{{ $group['fr-BE'] }}" />
            </url>
        @endforeach
    @endforeach

    @foreach ($watches as $watch)
        @php
            $watchGroup = [
                'fr-BE' => route('watches.show', ['watch' => $watch->id]),
                'nl-BE' => route('nl.watches.show', ['watch' => $watch->id]),
                'en-BE' => route('en.watches.show', ['watch' => $watch->id]),
            ];
        @endphp

        @foreach ($watchGroup as $currentUrl)
            <url>
                <loc>{{ $currentUrl }}</loc>
                @foreach ($watchGroup as $hreflang => $alternateUrl)
                    <xhtml:link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $alternateUrl }}" />
                @endforeach
                <xhtml:link rel="alternate" hreflang="x-default" href="{{ $watchGroup['fr-BE'] }}" />

                @if ($watch->updated_at)
                    <lastmod>{{ $watch->updated_at->toAtomString() }}</lastmod>
                @endif
            </url>
        @endforeach
    @endforeach
</urlset>
