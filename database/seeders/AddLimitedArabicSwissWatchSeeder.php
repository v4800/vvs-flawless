<?php

namespace Database\Seeders;

use App\Models\Watch;
use Illuminate\Database\Seeder;

class AddLimitedArabicSwissWatchSeeder extends Seeder
{
    public function run(): void
    {
        $slug = 'octogonale-arabe-edition-limitee';
        $folder = '090-octogonale-arabe-limited';
        $directory = '/images/watches/catalog/'.$folder.'/';
        $requiredImages = [
            '01-front.webp',
            '01-front-card.webp',
            '02-front-vertical.webp',
            '03-angle.webp',
            '04-side.webp',
            '05-clasp.webp',
        ];

        foreach ($requiredImages as $image) {
            $path = public_path(ltrim($directory.$image, '/'));

            if (! is_file($path)) {
                throw new \RuntimeException('Missing image: '.$path);
            }
        }

        $watch = Watch::query()
            ->where('slug', $slug)
            ->orWhere('image', 'like', $directory.'%')
            ->first();

        if ($watch === null) {
            $watch = new Watch;
        }

        $watch->slug = $slug;
        $watch->name = 'Octogonale argentée · Chiffres arabes dorés — Édition limitée';
        $watch->price = 1050;
        $watch->promo_price = null;
        $watch->japanese_price = null;
        $watch->japanese_promo_price = null;
        $watch->swiss_price = 1050;
        $watch->swiss_promo_price = null;
        $watch->description = 'Édition limitée à finition argentée, cadran pavé et chiffres arabes dorés, entièrement sertie de moissanite VVS. Mouvement suisse, boîtier et bracelet en acier inoxydable. Poids total annoncé des pierres : environ 25 à 30 TCW.';
        $watch->availability = 'Édition limitée';
        $watch->stock_quantity = null;
        $watch->image = $directory.'01-front.webp';
        $watch->save();

        $catalogPath = resource_path('data/watch-image-catalog.json');
        $contents = file_get_contents($catalogPath);

        if ($contents === false) {
            throw new \RuntimeException('Unable to read watch-image-catalog.json.');
        }

        /** @var array{next_watch_id?: int, watches: list<array<string, mixed>>} $catalog */
        $catalog = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        $entryIndex = null;
        $catalogId = null;

        foreach ($catalog['watches'] as $index => $catalogEntry) {
            if (($catalogEntry['slug'] ?? null) === $slug) {
                $entryIndex = $index;
                $catalogId = (int) ($catalogEntry['id'] ?? 0);
                break;
            }
        }

        if ($catalogId === null || $catalogId <= 0) {
            $catalogId = max(1, (int) ($catalog['next_watch_id'] ?? 1));
        }

        $entry = [
            'id' => $catalogId,
            'watch_id' => $watch->id,
            'slug' => $slug,
            'folder' => $folder,
            'images' => [
                '01-front.webp',
                '02-front-vertical.webp',
                '03-angle.webp',
                '04-side.webp',
                '05-clasp.webp',
            ],
            'featured' => false,
            'card_image' => '01-front-card.webp',
        ];

        if ($entryIndex === null) {
            $catalog['watches'][] = $entry;
        } else {
            $catalog['watches'][$entryIndex] = $entry;
        }

        $catalog['next_watch_id'] = max(
            (int) ($catalog['next_watch_id'] ?? 1),
            $catalogId + 1
        );

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
    }
}
