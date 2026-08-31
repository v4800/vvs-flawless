<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('watches.index') }}</loc>
    </url>

    @foreach ($watches as $watch)
        <url>
            <loc>{{ route('watches.show', ['watch' => $watch->id]) }}</loc>

            @if ($watch->updated_at)
                <lastmod>{{ $watch->updated_at->toAtomString() }}</lastmod>
            @endif
        </url>
    @endforeach
</urlset>