<?php

namespace Database\Seeders;

use App\Models\Watch;
use Illuminate\Database\Seeder;

class UpdateWatchDescriptionsSeeder extends Seeder
{
    public function run(): void
    {
        $descriptions = [

            // 42
            42 => 'Chronographe sportif au cadran camouflage et à la présence affirmée. Son sertissage en moissanite VVS couleur D apporte un éclat intense à une silhouette moderne et audacieuse.',

            // 43
            43 => 'Une finition bicolore associée à un cadran turquoise lumineux. La moissanite VVS couleur D vient renforcer son contraste et lui donner un style iced-out élégant et remarquable.',

            // 44
            44 => "Un modèle bicolore au cadran travaillé et à l'esthétique inspirée du voyage. Son sertissage en moissanite VVS couleur D lui apporte une allure technique, lumineuse et sophistiquée.",

            // 45
            45 => "Un cadran bleu roi profond entouré d'un sertissage éclatant. La moissanite VVS couleur D crée un contraste net entre élégance classique et esthétique iced-out contemporaine.",

            // 46
            46 => 'Une silhouette géométrique bicolore avec bracelet intégré et lignes marquées. La moissanite VVS couleur D accentue son architecture et son caractère sport-luxe.',

            // 47
            47 => 'Une carrure géométrique associée à un cadran texturé de style tapisserie. Le sertissage en moissanite VVS couleur D donne à cette pièce une présence sport-luxe particulièrement forte.',

            // 48
            48 => 'Une silhouette présidentielle bicolore sublimée par un cadran solaire lumineux. La moissanite VVS couleur D apporte une touche iced-out tout en conservant une allure raffinée.',

            // 49
            49 => 'Une finition or jaune assumée associée à une silhouette présidentielle. Son sertissage en moissanite VVS couleur D crée un rendu riche, lumineux et immédiatement visible au poignet.',

            // 50
            50 => 'Une finition or jaune associée à une lunette travaillée au relief cannelé. La moissanite VVS couleur D apporte une forte brillance tout en conservant une esthétique classique.',

            // 51
            51 => "L'association de l'or rose et du cadran vert olive donne à cette pièce une identité distinctive. La moissanite VVS couleur D apporte un contraste lumineux et raffiné.",

            // 52
            52 => "Un cadran bleu profond accompagné de chiffres romains pour une allure plus habillée. Le sertissage en moissanite VVS couleur D apporte l'éclat iced-out sans perdre son caractère classique.",
        ];

        foreach ($descriptions as $watchId => $description) {
            Watch::where('id', $watchId)
                ->update([
                    'description' => $description,
                ]);
        }
    }
}
