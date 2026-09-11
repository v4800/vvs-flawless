<?php

namespace App\Support;

use App\Models\Watch;

/**
 * @phpstan-type CatalogEntry array{
 *     id: int,
 *     slug: string,
 *     folder: string,
 *     images: list<string>,
 *     featured?: bool,
 *     card_image?: string,
 *     legacy_images?: list<string>
 * }
 */
final class WatchCatalog
{
    /** @var list<CatalogEntry>|null */
    private ?array $imageCatalog = null;

    public function __construct(
        private readonly LocalizedRoute $localizedRoute
    ) {}

    public function localizedWatch(Watch $watch): Watch
    {
        $translation = trans('watches.'.$watch->id);

        if (! is_array($translation)) {
            return $watch;
        }

        $localizedWatch = clone $watch;

        if (is_string($translation['name'] ?? null)) {
            $localizedWatch->name = $translation['name'];
        }

        if (is_string($translation['description'] ?? null)) {
            $localizedWatch->description = $translation['description'];
        }

        if (is_string($translation['short_description'] ?? null)) {
            $localizedWatch->setAttribute(
                'short_description',
                $translation['short_description']
            );
        }

        return $localizedWatch;
    }

    public function applyCover(Watch $watch): Watch
    {
        $entry = $this->catalogEntryForWatch($watch);

        if ($entry === null) {
            return $watch;
        }

        $gallery = $this->catalogGallery($entry);

        if ($gallery === []) {
            return $watch;
        }

        $watch = clone $watch;
        $watch->image = $gallery[0];
        $watch->setAttribute(
            'card_image',
            $this->catalogCardImage($entry, $gallery[0])
        );

        return $watch;
    }

    /**
     * Match an explicitly assigned image, never a database row number.
     *
     * @return list<string>
     */
    public function galleryForWatch(Watch $watch): array
    {
        $entry = $this->catalogEntryForWatch($watch);

        return $entry === null
            ? []
            : $this->catalogGallery($entry);
    }

    /**
     * @return array{
     *     reference: string,
     *     slug: string,
     *     name: string,
     *     image: string,
     *     cardImage: string,
     *     gallery: list<string>,
     *     detailUrl: string
     * }|null
     */
    public function catalogModel(string $slug): ?array
    {
        foreach ($this->catalogEntries() as $entry) {
            if ($entry['slug'] !== $slug) {
                continue;
            }

            $gallery = $this->catalogGallery($entry);

            if ($gallery === []) {
                return null;
            }

            return [
                'reference' => sprintf('VVS-C%03d', $entry['id']),
                'slug' => $entry['slug'],
                'name' => $this->catalogName($entry),
                'image' => $gallery[0],
                'cardImage' => $this->catalogCardImage($entry, $gallery[0]),
                'gallery' => $gallery,
                'detailUrl' => $this->catalogDetailUrl($entry),
            ];
        }

        return null;
    }

    /**
     * @param  iterable<Watch>  $watches
     * @return list<array{reference: string, name: string, image: string, cardImage: string, detailUrl: string, watchUrl: string|null}>
     */
    public function featuredModels(iterable $watches): array
    {
        $models = [];

        foreach ($this->catalogEntries() as $entry) {
            if (! ($entry['featured'] ?? false)) {
                continue;
            }

            $gallery = $this->catalogGallery($entry);

            if ($gallery === []) {
                continue;
            }

            $watchUrl = null;

            foreach ($watches as $watch) {
                if ($watch->image === $gallery[0]) {
                    $watchUrl = route(
                        $this->localizedRoute->name('watches.show'),
                        $watch
                    );
                    break;
                }
            }

            $models[] = [
                'reference' => sprintf('VVS-C%03d', $entry['id']),
                'name' => $this->catalogName($entry),
                'image' => $gallery[0],
                'cardImage' => $this->catalogCardImage($entry, $gallery[0]),
                'detailUrl' => $this->catalogDetailUrl($entry),
                'watchUrl' => $watchUrl,
            ];
        }

        return $models;
    }

    /**
     * @return list<CatalogEntry>
     */
    private function catalogEntries(): array
    {
        if ($this->imageCatalog !== null) {
            return $this->imageCatalog;
        }

        $contents = file_get_contents(
            resource_path('data/watch-image-catalog.json')
        );

        if ($contents === false) {
            return [];
        }

        /** @var array{watches: list<CatalogEntry>} $catalog */
        $catalog = json_decode(
            $contents,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->imageCatalog = $catalog['watches'];

        return $this->imageCatalog;
    }

    /**
     * @return CatalogEntry|null
     */
    private function catalogEntryForWatch(Watch $watch): ?array
    {
        if (! is_string($watch->image) || $watch->image === '') {
            return null;
        }

        $image = '/'.ltrim($watch->image, '/');

        foreach ($this->catalogEntries() as $entry) {
            $directory = '/images/watches/catalog/'.$entry['folder'].'/';

            if (str_starts_with($image, $directory)
                || in_array($image, $entry['legacy_images'] ?? [], true)) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * @param  CatalogEntry  $entry
     * @return list<string>
     */
    private function catalogGallery(array $entry): array
    {
        $directory = '/images/watches/catalog/'.$entry['folder'].'/';

        return array_values(array_filter(
            array_map(
                fn (string $image): string => $directory.$image,
                $entry['images']
            ),
            fn (string $image): bool => $this->catalogImageExists($image)
        ));
    }

    /**
     * @param  CatalogEntry  $entry
     */
    private function catalogCardImage(array $entry, string $fallback): string
    {
        $cardImage = '/images/watches/catalog/'
            .$entry['folder'].'/'
            .($entry['card_image'] ?? $entry['images'][0]);

        return $this->catalogImageExists($cardImage)
            ? $cardImage
            : $fallback;
    }

    /**
     * @param  CatalogEntry  $entry
     */
    private function catalogName(array $entry): string
    {
        $name = trans('site.collection.catalog_names.'.$entry['slug']);

        return is_string($name) ? $name : $entry['slug'];
    }

    /**
     * @param  CatalogEntry  $entry
     */
    private function catalogDetailUrl(array $entry): string
    {
        return route(
            $this->localizedRoute->name('catalog.show'),
            ['catalogSlug' => $entry['slug']]
        );
    }

    private function catalogImageExists(string $image): bool
    {
        return is_file(public_path(ltrim($image, '/')));
    }
}
