<?php

namespace Database\Seeders;

use App\Models\Watch;
use Illuminate\Database\Seeder;

class FeaturedCatalogWatchesSeeder extends Seeder
{
    public function run(): void
    {
        $catalogPath = resource_path('data/watch-image-catalog.json');
        $contents = file_get_contents($catalogPath);

        if ($contents === false) {
            throw new \RuntimeException('Unable to read watch-image-catalog.json.');
        }

        /** @var array{watches: list<array{id: int, folder: string, images: list<string>}>} $catalog */
        $catalog = json_decode(
            $contents,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $models = [
            2 => [
                'name' => '41 mm · Bicolore, cadran champagne',
                'japanese_price' => 650,
                'swiss_price' => 1000,
                'description' => 'Une montre 41 mm à finition bicolore, cadran champagne et bracelet intégré, sertie de moissanite VVS couleur D.',
            ],
            4 => [
                'name' => '41 mm · Carrée noire, chiffres romains',
                'japanese_price' => 850,
                'swiss_price' => 1200,
                'description' => 'Une montre 41 mm carrée noire à chiffres romains, sertie de moissanite VVS couleur D.',
            ],
            8 => [
                'name' => '41 mm · Chronographe, bracelet noir',
                'japanese_price' => 650,
                'swiss_price' => 1000,
                'description' => 'Un chronographe 41 mm à bracelet noir et boîtier serti de moissanite VVS couleur D.',
            ],
        ];

        foreach ($catalog['watches'] as $entry) {
            $model = $models[$entry['id']] ?? null;

            if ($model === null || ($entry['images'][0] ?? null) === null) {
                continue;
            }

            $directory = '/images/watches/catalog/'.$entry['folder'].'/';

            $existing = Watch::query()
                ->where('image', 'like', $directory.'%')
                ->first();

            if ($existing !== null) {
                $existing->name = $model['name'];
                $existing->description = $model['description'];
                $existing->save();

                continue;
            }

            Watch::query()->create([
                'name' => $model['name'],
                'price' => $model['japanese_price'],
                'japanese_price' => $model['japanese_price'],
                'swiss_price' => $model['swiss_price'],
                'description' => $model['description'],
                'availability' => 'Sur commande',
                'stock_quantity' => null,
                'image' => $directory.$entry['images'][0],
            ]);
        }
    }
}
