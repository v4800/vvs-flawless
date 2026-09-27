<?php

namespace Database\Seeders;

use App\Models\Watch;
use Illuminate\Database\Seeder;

class VvsCatalogCorrections20260924Seeder extends Seeder
{
    public function run(): void
    {
        $updates = [
            'octogonale-arabe-edition-limitee' => [
                'name' => 'Octogonale argentée · Chiffres arabes dorés — Édition limitée',
                'price' => 1350,
                'promo_price' => null,
                'japanese_price' => null,
                'japanese_promo_price' => null,
                'swiss_price' => 1350,
                'swiss_promo_price' => null,
                'availability' => 'Sur commande',
                'description' => 'Modèle personnalisé à finition argentée, cadran pavé et chiffres arabes dorés, entièrement serti de moissanite VVS. Disponible exclusivement avec mouvement suisse.',
            ],

            '41-mm-geometrique-cadran-champagne' => [
                'name' => '41 mm · Géométrique, cadran champagne',
                'price' => 850,
                'promo_price' => null,
                'japanese_price' => 850,
                'japanese_promo_price' => null,
                'swiss_price' => null,
                'swiss_promo_price' => null,
                'availability' => 'Sur commande',
                'description' => 'Modèle personnalisé 41 mm à carrure géométrique, cadran champagne et bracelet intégré serti de moissanite VVS couleur D.',
            ],

            '41-mm-cadran-bleu-roi-bracelet-argente' => [
                'name' => '41 mm · Cadran rouge, bracelet argenté',
                'price' => 850,
                'promo_price' => null,
                'japanese_price' => 850,
                'japanese_promo_price' => null,
                'swiss_price' => null,
                'swiss_promo_price' => null,
                'availability' => 'Sur commande',
                'description' => 'Modèle personnalisé 41 mm avec cadran rouge, bracelet argenté et sertissage en moissanite VVS couleur D.',
            ],

            '41-mm-chronographe-bracelet-noir' => [
                'name' => '41 mm · Chronographe, bracelet noir',
                'price' => 650,
                'promo_price' => null,
                'japanese_price' => 650,
                'japanese_promo_price' => null,
                'swiss_price' => null,
                'swiss_promo_price' => null,
            ],
        ];

        foreach ($updates as $slug => $values) {
            Watch::query()
                ->where('slug', $slug)
                ->update($values);
        }
    }
}