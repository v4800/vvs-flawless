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
            42 => 'Un chronographe sportif au cadran camouflage, pensé pour un look affirmé. La moissanite VVS couleur D ajoute un éclat franc sans masquer les détails du cadran.',

            // 43
            43 => 'Le cadran turquoise apporte la couleur, tandis que la finition bicolore garde l’ensemble équilibré. Le sertissage en moissanite VVS couleur D accentue le contraste.',

            // 44
            44 => 'Un modèle bicolore à double fuseau, avec un cadran riche en détails. La moissanite VVS couleur D lui donne une présence lumineuse et technique.',

            // 45
            45 => 'Le bleu roi contraste nettement avec le sertissage en moissanite VVS couleur D. Une option plus classique, avec une finition iced-out bien visible.',

            // 46
            46 => 'Sa carrure géométrique et son bracelet intégré lui donnent une ligne sportive. Le sertissage en moissanite VVS couleur D souligne cette silhouette.',

            // 47
            47 => 'Le cadran texturé apporte du relief à cette silhouette géométrique. La moissanite VVS couleur D renforce son caractère sport et habillé.',

            // 48
            48 => 'Une finition bicolore associée à un cadran solaire lumineux. La moissanite VVS couleur D ajoute l’éclat bustdown tout en gardant une allure soignée.',

            // 49
            49 => 'Une finition couleur or jaune pour un rendu assumé au poignet. Le sertissage en moissanite VVS couleur D apporte une brillance nette sur l’ensemble.',

            // 50
            50 => 'La lunette cannelée donne du relief à cette finition couleur or jaune. La moissanite VVS couleur D complète le modèle avec un éclat plus marqué.',

            // 51
            51 => 'Le cadran vert olive contraste avec la finition couleur or rose. La moissanite VVS couleur D apporte de la lumière sans effacer cette association de couleurs.',

            // 52
            52 => 'Le cadran bleu et les chiffres romains donnent au modèle une allure plus habillée. Le sertissage en moissanite VVS couleur D y ajoute une finition iced-out.',
        ];

        foreach ($descriptions as $watchId => $description) {
            Watch::where('id', $watchId)
                ->update([
                    'description' => $description,
                ]);
        }
    }
}
