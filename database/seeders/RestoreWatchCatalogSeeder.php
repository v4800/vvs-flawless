<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use RuntimeException;

class RestoreWatchCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalogContents = file_get_contents(
            resource_path('data/watch-image-catalog.json')
        );

        if ($catalogContents === false) {
            throw new RuntimeException(
                'Impossible de lire le catalogue des images.'
            );
        }

        /** @var array{watches?: list<array<string, mixed>>} $catalog */
        $catalog = json_decode(
            $catalogContents,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $entries = collect($catalog['watches'] ?? [])
            ->keyBy(
                static fn (array $entry): string => (string) ($entry['slug'] ?? '')
            );

        /*
         * Les 3 premières sont déjà restaurées :
         * catalog 2 -> DB #1
         * catalog 4 -> DB #2
         * catalog 8 -> DB #3
         *
         * Ici : les 17 restantes.
         */
        $models = [
            'blue-round' => [
                'id' => 52,
                'copy' => 'id',
                'price' => 950,
                'japanese_price' => 950,
                'swiss_price' => null,
            ],

            'square-roman' => [
                'id' => 53,
                'copy' => 'catalog',
                'price' => 900,
                'japanese_price' => 900,
                'swiss_price' => null,
            ],

            'black-stainless' => [
                'id' => 54,
                'copy' => 'catalog',
                'price' => null,
                'japanese_price' => null,
                'swiss_price' => null,
            ],

            'red-roman' => [
                'id' => 55,
                'copy' => 'catalog',
                'price' => null,
                'japanese_price' => null,
                'swiss_price' => null,
            ],

            'jubilee' => [
                'id' => 56,
                'copy' => 'catalog',
                'price' => null,
                'japanese_price' => null,
                'swiss_price' => null,
            ],

            'gold-black-daydate' => [
                'id' => 43,
                'copy' => 'id',
                'price' => 950,
                'japanese_price' => 950,
                'swiss_price' => null,
            ],

            'rose-skeleton' => [
                'id' => 57,
                'copy' => 'catalog',
                'price' => 950,
                'japanese_price' => 950,
                'swiss_price' => null,
            ],

            'square-bicolor-paved' => [
                'id' => 58,
                'copy' => 'catalog',
                'price' => 950,
                'japanese_price' => 950,
                'swiss_price' => null,
            ],

            'women-pink' => [
                'id' => 59,
                'copy' => 'catalog',
                'price' => 950,
                'japanese_price' => 950,
                'swiss_price' => null,
            ],

            'women-square-36' => [
                'id' => 60,
                'copy' => 'catalog',
                'price' => 950,
                'japanese_price' => 950,
                'swiss_price' => null,
            ],

            'presidentielle-bicolore-romains' => [
                'id' => 49,
                'copy' => 'presented',
                'price' => 650,
                'japanese_price' => null,
                'swiss_price' => null,
            ],

            'geometrique-bicolore' => [
                'id' => 46,
                'copy' => 'id',
                'price' => 900,
                'japanese_price' => 900,
                'swiss_price' => null,
            ],

            'presidentielle-bicolore-champagne' => [
                'id' => 50,
                'copy' => 'id',
                'price' => 950,
                'japanese_price' => 950,
                'swiss_price' => null,
            ],

            'cadran-bleu-roi' => [
                'id' => 61,
                'copy' => 'catalog',
                'price' => 900,
                'japanese_price' => 900,
                'swiss_price' => null,
            ],

            'chronographe-camouflage' => [
                'id' => 42,
                'copy' => 'id',
                'price' => 950,
                'japanese_price' => 950,
                'swiss_price' => 1250,
            ],

            'bleue-argent-romains' => [
                'id' => 51,
                'copy' => 'id',
                'price' => 900,
                'japanese_price' => 900,
                'swiss_price' => null,
            ],

            'geometrique-or-jaune-cadran-vert' => [
                'id' => 47,
                'copy' => 'id',
                'price' => 900,
                'japanese_price' => 900,
                'swiss_price' => null,
            ],
        ];

        /*
         * Vérification complète AVANT transaction.
         */
        foreach ($models as $catalogSlug => $model) {
            $entry = $entries->get($catalogSlug);

            if (! is_array($entry)) {
                throw new RuntimeException(
                    'Catalogue introuvable : '.$catalogSlug
                );
            }

            foreach ($entry['images'] ?? [] as $image) {
                $path = public_path(
                    'images/watches/catalog/'
                    .$entry['folder'].'/'.$image
                );

                if (! is_file($path)) {
                    throw new RuntimeException(
                        'Image manquante : '.$path
                    );
                }
            }

            if (($entry['images'][0] ?? null) === null) {
                throw new RuntimeException(
                    'Première image absente : '.$catalogSlug
                );
            }
        }

        DB::transaction(function () use ($models, $entries): void {
            foreach ($models as $catalogSlug => $model) {
                $entry = $entries->get($catalogSlug);

                if (
                    ! is_array($entry)
                    || ! is_string($entry['folder'] ?? null)
                    || ! is_array($entry['images'] ?? null)
                    || ! is_string($entry['images'][0] ?? null)
                ) {
                    throw new RuntimeException(
                        'Entrée de catalogue invalide pour '.$catalogSlug.'.'
                    );
                }

                $copyKey = match ($model['copy']) {
                    'presented' => 'watches.presented',
                    'id' => 'watches.'.$model['id'],
                    default => 'watches.catalog.'.$catalogSlug,
                };

                $copy = Lang::get(
                    $copyKey,
                    [],
                    'fr_BE',
                    false
                );

                if (! is_array($copy)) {
                    throw new RuntimeException(
                        'Copy FR introuvable : '.$copyKey
                    );
                }

                $name = $copy['name'] ?? null;

                if (! is_string($name) || trim($name) === '') {
                    $name = Lang::get(
                        'site.collection.catalog_names.'.$catalogSlug,
                        [],
                        'fr_BE',
                        false
                    );
                }

                $description = $copy['description'] ?? null;

                if (
                    ! is_string($name)
                    || trim($name) === ''
                    || ! is_string($description)
                    || trim($description) === ''
                ) {
                    throw new RuntimeException(
                        'Nom/description FR manquant : '.$catalogSlug
                    );
                }

                $image =
                    '/images/watches/catalog/'
                    .$entry['folder'].'/'
                    .$entry['images'][0];

                $slug = Str::slug($name);

                $slugOwner = DB::table('watches')
                    ->where('slug', $slug)
                    ->where('id', '!=', $model['id'])
                    ->first();

                if ($slugOwner !== null) {
                    throw new RuntimeException(
                        'Collision slug : '.$slug
                    );
                }

                $exists = DB::table('watches')
                    ->where('id', $model['id'])
                    ->exists();

                $data = [
                    'name' => $name,
                    'slug' => $slug,
                    'price' => $model['price'],
                    'promo_price' => null,

                    'japanese_price' => $model['japanese_price'],

                    'japanese_promo_price' => null,

                    'swiss_price' => $model['swiss_price'],

                    'swiss_promo_price' => null,

                    'description' => $description,
                    'availability' => 'Sur commande',
                    'stock_quantity' => null,
                    'image' => $image,
                    'updated_at' => now(),
                ];

                if (! $exists) {
                    $data['created_at'] = now();
                }

                DB::table('watches')->updateOrInsert(
                    ['id' => $model['id']],
                    $data
                );

                $this->command->info(
                    '#'.$model['id']
                    .' '.$catalogSlug
                    .' -> '.$image
                );
            }
        });

        if (DB::table('watches')->count() !== 20) {
            throw new RuntimeException(
                'La restauration doit terminer avec exactement 20 watches.'
            );
        }

        $this->command->info(
            'Restauration terminée : 20 watches.'
        );
    }
}
