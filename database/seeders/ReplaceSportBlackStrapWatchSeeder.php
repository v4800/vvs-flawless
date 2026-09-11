<?php

namespace Database\Seeders;

use App\Models\Watch;
use Illuminate\Database\Seeder;

class ReplaceSportBlackStrapWatchSeeder extends Seeder
{
    public function run(): void
    {
        $folder = '008-sport-black-strap';
        $directory = '/images/watches/catalog/'.$folder.'/';
        $requiredImages = [
            '02-chronograph-front.webp',
            '02-chronograph-card.webp',
            '03-chronograph-side.webp',
            '04-chronograph-clasp.webp',
        ];

        foreach ($requiredImages as $image) {
            $path = public_path(ltrim($directory.$image, '/'));

            if (! is_file($path)) {
                throw new \RuntimeException('Missing image: '.$path);
            }
        }

        $catalogPath = resource_path('data/watch-image-catalog.json');
        $contents = file_get_contents($catalogPath);

        if ($contents === false) {
            throw new \RuntimeException('Unable to read watch-image-catalog.json.');
        }

        /** @var array{watches: list<array<string, mixed>>} $catalog */
        $catalog = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        $found = false;

        foreach ($catalog['watches'] as &$entry) {
            if (($entry['slug'] ?? null) !== 'sport-black-strap') {
                continue;
            }

            $entry['images'] = [
                '02-chronograph-front.webp',
                '03-chronograph-side.webp',
                '04-chronograph-clasp.webp',
            ];
            $entry['card_image'] = '02-chronograph-card.webp';
            $entry['archived_images'] = array_values(array_unique(array_merge(
                $entry['archived_images'] ?? [],
                ['01-black-marble.webp', '01-black-marble-card.webp']
            )));
            $entry['featured'] = true;
            $found = true;
            break;
        }
        unset($entry);

        if (! $found) {
            throw new \RuntimeException('Catalog entry sport-black-strap was not found.');
        }

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

        $watch = Watch::query()
            ->where('image', 'like', $directory.'%')
            ->first()
            ?? Watch::query()
                ->where('name', 'like', '%Sportive%bracelet noir%')
                ->first();

        if ($watch === null) {
            throw new \RuntimeException('The current Sportive, bracelet noir watch could not be found.');
        }

        $watch->name = '41 mm · Chronographe, bracelet noir';
        $watch->description = 'Chronographe 41 mm à bracelet noir, boîtier entièrement serti et cadran pavé en moissanite VVS couleur D. Les poussoirs noirs renforcent le contraste sportif du modèle.';
        $watch->image = $directory.'02-chronograph-front.webp';
        $watch->save();
    }
}
