<?php

namespace App\Support;

use App\Models\Watch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

/**
 * @phpstan-type CatalogEntry array{
 *     id: int,
 *     watch_id?: int,
 *     slug: string,
 *     folder: string,
 *     images: list<string>,
 *     featured?: bool,
 *     card_image?: string,
 *     legacy_images?: list<string>,
 *     archived_images?: list<string>
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
        if (PresentedWatch::matches($watch)) {
            return PresentedWatch::localize($watch);
        }

        $entry = $this->catalogEntryForWatch($watch);
        $translation = $this->translationForWatch($watch, $entry);
        $catalogKey = $entry === null
            ? null
            : 'site.collection.catalog_names.'.$entry['slug'];
        $locale = app()->getLocale();
        $catalogName = is_string($catalogKey)
            && Lang::has($catalogKey, $locale, false)
            ? Lang::get($catalogKey, [], $locale, false)
            : null;

        if (! is_array($translation) && ! is_string($catalogName)) {
            return $watch;
        }

        $translation = is_array($translation) ? $translation : [];

        if ($locale !== 'fr_BE') {
            foreach (['description', 'short_description'] as $field) {
                if (! is_string($translation[$field] ?? null)) {
                    throw new \LogicException(sprintf(
                        'Missing %s translation for watch #%d in locale %s.',
                        $field,
                        $watch->id,
                        $locale
                    ));
                }
            }
        }

        $localizedWatch = clone $watch;

        if (is_string($translation['name'] ?? null)) {
            $localizedWatch->name = $translation['name'];
        } elseif (is_string($catalogName)) {
            $localizedWatch->name = $catalogName;
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

    /**
     * Localize every Watch model found inside Inertia props.
     */
    public function localizeNestedWatches(mixed $value): mixed
    {
        if ($value instanceof Watch) {
            return $this->localizedWatch($value)->toArray();
        }

        if ($value instanceof Collection) {
            return $value->map(
                fn (mixed $item): mixed => $this->localizeNestedWatches($item)
            );
        }

        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->localizeNestedWatches($item);
            }

            return $value;
        }

        return $value;
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
     * @param  iterable<Watch>  $watches
     * @return list<array{reference: string, name: string, image: string, cardImage: string, watchUrl: string|null}>
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

            $name = trans('site.collection.catalog_names.'.$entry['slug']);
            $name = is_string($name) ? $name : $entry['slug'];
            $models[] = [
                'reference' => sprintf('VVS-C%03d', $entry['id']),
                'name' => $name,
                'image' => $gallery[0],
                'cardImage' => $this->catalogCardImage($entry, $gallery[0]),
                'watchUrl' => $watchUrl,
            ];
        }

        return $models;
    }

    /**
     * @return array{
     *     families: list<array{value: string, label: string}>,
     *     aliases: list<string>,
     *     keywords: list<string>
     * }
     */
    public function searchMetadataForWatch(Watch $watch): array
    {
        $entry = $this->catalogEntryForWatch($watch);

        if ($entry === null) {
            return [
                'families' => [],
                'aliases' => [],
                'keywords' => [],
            ];
        }

        return $this->searchMetadataForSlug($entry['slug']);
    }


    /**
     * @return array{
     *     families: list<array{value: string, label: string}>,
     *     aliases: list<string>,
     *     keywords: list<string>
     * }
     */
    private function searchMetadataForSlug(string $slug): array
    {
        $models = config('watch_search.models', []);
        $metadata = is_array($models)
            && is_array($models[$slug] ?? null)
            ? $models[$slug]
            : [];

        $families = array_values(array_filter(
            $metadata['families'] ?? [],
            static fn (mixed $family): bool =>
                is_array($family)
                && is_string($family['value'] ?? null)
                && trim($family['value']) !== ''
                && is_string($family['label'] ?? null)
                && trim($family['label']) !== ''
        ));

        $aliases = array_values(array_filter(
            $metadata['aliases'] ?? [],
            static fn (mixed $alias): bool =>
                is_string($alias) && trim($alias) !== ''
        ));

        $keywords = array_values(array_filter(
            $metadata['keywords'] ?? [],
            static fn (mixed $keyword): bool =>
                is_string($keyword) && trim($keyword) !== ''
        ));

        return [
            'families' => $families,
            'aliases' => $aliases,
            'keywords' => $keywords,
        ];
    }

    /**
     * Resolve only the requested locale. Never let Laravel silently fall back
     * to fr_BE for public product copy in NL/EN/DE.
     *
     * @param  CatalogEntry|null  $entry
     * @return array<string, mixed>
     */
    private function translationForWatch(Watch $watch, ?array $entry): array
    {
        $locale = app()->getLocale();
        $keys = ['watches.'.$watch->id];

        if ($entry !== null) {
            $keys[] = 'watches.catalog.'.$entry['slug'];
        }

        foreach ($keys as $key) {
            if (! Lang::has($key, $locale, false)) {
                continue;
            }

            $translation = Lang::get($key, [], $locale, false);

            if (is_array($translation)) {
                return $translation;
            }
        }

        if ($locale === 'fr_BE') {
            return [];
        }

        throw new \LogicException(sprintf(
            'Missing localized watch copy for watch #%d in locale %s.',
            $watch->id,
            $locale
        ));
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

        foreach ($this->catalogEntries() as $entry) {
            if (($entry['watch_id'] ?? null) === $watch->id) {
                return $entry;
            }
        }

        $image = '/'.ltrim($watch->image, '/');

        foreach ($this->catalogEntries() as $entry) {
            if (isset($entry['watch_id'])) {
                continue;
            }

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

    private function catalogImageExists(string $image): bool
    {
        return is_file(public_path(ltrim($image, '/')));
    }
}
