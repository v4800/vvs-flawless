<?php

namespace Database\Seeders;

use App\Models\Watch;
use Illuminate\Database\Seeder;

class ReplaceTurquoiseWatchSeeder extends Seeder
{
    public function run(): void
    {
        $folder = '009-gold-black-daydate';
        $directory = '/images/watches/catalog/'.$folder.'/';
        $requiredImages = [
            '01-front.webp',
            '01-front-card.webp',
            '02-angle.webp',
            '03-back.webp',
        ];

        foreach ($requiredImages as $image) {
            $path = public_path(ltrim($directory.$image, '/'));

            if (! is_file($path)) {
                throw new \RuntimeException(
                    'Missing image: '.$path.'. Extract the gold-black model assets before running this seeder.'
                );
            }
        }

        $watch = Watch::query()->find(43)
            ?? Watch::query()
                ->where('image', '/images/watches/bicolore-turquoise.webp')
                ->first()
            ?? Watch::query()
                ->where('image', '/images/watches/bicolore-turquoise.png')
                ->first()
            ?? Watch::query()
                ->where('name', 'like', '%turquoise%')
                ->first();

        if ($watch === null) {
            throw new \RuntimeException(
                'The turquoise watch could not be found in the watches table.'
            );
        }

        $catalogPath = resource_path('data/watch-image-catalog.json');
        $contents = file_get_contents($catalogPath);

        if ($contents === false) {
            throw new \RuntimeException(
                'Unable to read watch-image-catalog.json.'
            );
        }

        /** @var array{next_watch_id?: int, watches: list<array<string, mixed>>} $catalog */
        $catalog = json_decode(
            $contents,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $entry = [
            'id' => 9,
            'slug' => 'gold-black-daydate',
            'folder' => $folder,
            'images' => [
                '01-front.webp',
                '02-angle.webp',
                '03-back.webp',
            ],
            'featured' => false,
            'card_image' => '01-front-card.webp',
            'legacy_images' => [
                '/images/watches/bicolore-turquoise.webp',
                '/images/watches/bicolore-turquoise.png',
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

        $catalog['next_watch_id'] = max(
            (int) ($catalog['next_watch_id'] ?? 1),
            10
        );

        $encodedCatalog = json_encode(
            $catalog,
            JSON_PRETTY_PRINT
                | JSON_UNESCAPED_SLASHES
                | JSON_UNESCAPED_UNICODE
                | JSON_THROW_ON_ERROR
        );

        if (file_put_contents($catalogPath, $encodedCatalog.PHP_EOL) === false) {
            throw new \RuntimeException(
                'Unable to update watch-image-catalog.json.'
            );
        }

        $desiredSlug = '41-mm-or-jaune-cadran-noir';
        $slug = $desiredSlug;
        $suffix = 2;

        while (
            Watch::query()
                ->where('slug', $slug)
                ->where($watch->getKeyName(), '!=', $watch->getKey())
                ->exists()
        ) {
            $slug = $desiredSlug.'-'.$suffix;
            $suffix++;
        }

        $watch->name = '41 mm · Or jaune, cadran noir';
        $watch->description = 'Finition or jaune, cadran noir et sertissage en moissanite VVS couleur D. Le bracelet conserve un centre poli avec des maillons extérieurs sertis pour un contraste net.';
        $watch->image = $directory.'01-front.webp';
        $watch->slug = $slug;
        $watch->save();
    }
}
