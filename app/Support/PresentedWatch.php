<?php

namespace App\Support;

use App\Models\Watch;

final class PresentedWatch
{
    public const DIRECTORY = '/images/watches/catalog/014-presidentielle-bicolore-romains/';

    public static function matches(Watch $watch): bool
    {
        // Only the newly installed image activates this offer; never use a row ID.
        return $watch->image === self::DIRECTORY.'vvs-carree-cuir-front-v1.webp';
    }

    public static function localize(Watch $watch): Watch
    {
        $copy = clone $watch;
        [$name, $description] = match (app()->getLocale()) {
            'nl_BE' => ['Vierkant · Witte wijzerplaat, zwarte leren band', 'Vierkante kast met moissaniet, Romeinse cijfers en een zwarte leren band. Opgegeven totaal steengewicht: 20–30 ct, te bevestigen voor dit exemplaar.'],
            'en_BE' => ['Square · White dial, black leather strap', 'Square moissanite-set case, Roman numerals and a black leather strap. Stated total stone weight: 20–30 ct, to be confirmed for this individual watch.'],
            'de_BE' => ['Quadratisch · Weißes Zifferblatt, schwarzes Lederband', 'Quadratisches Gehäuse mit Moissanit, römische Ziffern und schwarzes Lederband. Angegebenes Gesamtsteingewicht: 20–30 ct, für dieses Exemplar zu bestätigen.'],
            default => ['Carrée · Cadran blanc, bracelet cuir noir', 'Boîtier carré serti de moissanite, chiffres romains et bracelet en cuir noir. Poids total annoncé des pierres : 20–30 ct, à confirmer pour cet exemplaire.'],
        };
        $copy->name = $name;
        $copy->description = $description;
        $copy->setAttribute('short_description', $description);
        $copy->setAttribute('single_offer', true);

        return $copy;
    }
}
