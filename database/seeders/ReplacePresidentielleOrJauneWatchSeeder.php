<?php

namespace Database\Seeders;

use App\Models\Watch;
use Illuminate\Database\Seeder;

class ReplacePresidentielleOrJauneWatchSeeder extends Seeder
{
    public function run(): void
    {
        $folder = '014-presidentielle-bicolore-romains';
        $directory = '/images/watches/catalog/'.$folder.'/';
        $requiredImages = [
            '01-front.webp',
            '01-front-card.webp',
            '02-front-vertical.webp',
            '03-side.webp',
            '04-bracelet.webp',
        ];

        foreach ($requiredImages as $image) {
            $path = public_path(ltrim($directory.$image, '/'));

            if (! is_file($path)) {
                throw new \RuntimeException(
                    'Missing image: '.$path.'. Extract the replacement assets before running this seeder.'
                );
            }
        }

        $catalogPath = resource_path('data/watch-image-catalog.json');
        $contents = file_get_contents($catalogPath);

        if ($contents === false) {
            throw new \RuntimeException('Unable to read watch-image-catalog.json.');
        }

        /** @var array{next_watch_id?: int, watches: list<array<string, mixed>>} $catalog */
        $catalog = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        $entry = [
            'id' => 14,
            'slug' => 'presidentielle-bicolore-romains',
            'folder' => $folder,
            'images' => [
                '01-front.webp',
                '02-front-vertical.webp',
                '03-side.webp',
                '04-bracelet.webp',
            ],
            'featured' => false,
            'card_image' => '01-front-card.webp',
            'legacy_images' => [
                '/images/watches/presidentielle-or-jaune.webp',
                '/images/watches/presidentielle-or-jaune.png',
            ],
        ];

        $entryIndex = null;

        foreach ($catalog['watches'] as $index => $catalogEntry) {
            if (($catalogEntry['slug'] ?? null) === $entry['slug']) {
                $entryIndex = $index;
                break;
            }
        }

        if ($entryIndex === null) {
            $catalog['watches'][] = $entry;
        } else {
            $catalog['watches'][$entryIndex] = $entry;
        }

        $catalog['next_watch_id'] = max((int) ($catalog['next_watch_id'] ?? 1), 15);

        $encodedCatalog = json_encode(
            $catalog,
            JSON_PRETTY_PRINT
                | JSON_UNESCAPED_SLASHES
                | JSON_UNESCAPED_UNICODE
                | JSON_THROW_ON_ERROR
        );

        if (file_put_contents($catalogPath, $encodedCatalog.PHP_EOL) === false) {
            throw new \RuntimeException('Unable to update watch-image-catalog.json.');
        }

        $watch = Watch::query()->find(49)
            ?? Watch::query()
                ->whereIn('image', [
                    '/images/watches/presidentielle-or-jaune.webp',
                    '/images/watches/presidentielle-or-jaune.png',
                ])
                ->first();

        if ($watch === null) {
            throw new \RuntimeException('The presidential yellow-gold watch to replace was not found.');
        }

        $watch->name = '41 mm · Présidentielle bicolore, chiffres romains';
        $watch->description = 'Modèle bicolore argent et or jaune avec cadran pavé à chiffres romains, lunette sertie et bracelet entièrement iced-out en moissanite VVS couleur D.';
        $watch->image = $directory.'01-front.webp';
        $watch->save();
    }
}
