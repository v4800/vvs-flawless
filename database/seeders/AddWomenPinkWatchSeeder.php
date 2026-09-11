<?php

namespace Database\Seeders;

use App\Models\Watch;
use Illuminate\Database\Seeder;

class AddWomenPinkWatchSeeder extends Seeder
{
    public function run(): void
    {
        $folder = '012-women-pink';
        $directory = '/images/watches/catalog/'.$folder.'/';
        $requiredImages = [
            '01-front.webp',
            '01-front-card.webp',
            '02-bracelet-vertical.webp',
            '03-clasp.webp',
        ];

        foreach ($requiredImages as $image) {
            $path = public_path(ltrim($directory.$image, '/'));

            if (! is_file($path)) {
                throw new \RuntimeException(
                    'Missing image: '.$path.'. Extract the women-pink model assets before running this seeder.'
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
            'id' => 12,
            'slug' => 'women-pink',
            'folder' => $folder,
            'images' => [
                '01-front.webp',
                '02-bracelet-vertical.webp',
                '03-clasp.webp',
            ],
            'featured' => true,
            'card_image' => '01-front-card.webp',
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

        $catalog['next_watch_id'] = max((int) ($catalog['next_watch_id'] ?? 1), 13);

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

        $existing = Watch::query()
            ->where('image', 'like', $directory.'%')
            ->first();

        if ($existing !== null) {
            $existing->name = '36 mm · Femme, cadran rose';
            $existing->description = 'Modèle femme 36 mm avec cadran rose, bracelet entièrement pavé et sertissage en moissanite VVS couleur D.';
            $existing->image = $directory.'01-front.webp';
            $existing->save();

            return;
        }

        $pricingSource = Watch::query()->find(43);

        if ($pricingSource === null) {
            throw new \RuntimeException(
                'Pricing source watch ID 43 was not found. Add the model after your existing product data is loaded.'
            );
        }

        $japanesePrice = $pricingSource->japanese_price ?? $pricingSource->price;
        $swissPrice = $pricingSource->swiss_price;

        if ($japanesePrice === null || $swissPrice === null) {
            throw new \RuntimeException(
                'Watch ID 43 does not have both Japanese and Swiss prices.'
            );
        }

        Watch::query()->create([
            'name' => '36 mm · Femme, cadran rose',
            'price' => $japanesePrice,
            'promo_price' => $pricingSource->promo_price,
            'japanese_price' => $japanesePrice,
            'japanese_promo_price' => $pricingSource->japanese_promo_price,
            'swiss_price' => $swissPrice,
            'swiss_promo_price' => $pricingSource->swiss_promo_price,
            'description' => 'Modèle femme 36 mm avec cadran rose, bracelet entièrement pavé et sertissage en moissanite VVS couleur D.',
            'availability' => $pricingSource->availability ?: 'Sur commande',
            'stock_quantity' => null,
            'image' => $directory.'01-front.webp',
        ]);
    }
}
