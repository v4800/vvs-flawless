<?php

namespace App\Support;

use App\Models\Watch;
use Illuminate\Support\Facades\Lang;

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
        $locale = app()->getLocale();
        $key = 'watches.presented';

        if (! Lang::has($key, $locale, false)) {
            throw new \LogicException(
                'Missing presented watch translation for locale '.$locale.'.'
            );
        }

        $translation = Lang::get($key, [], $locale, false);

        if (! is_array($translation)
            || ! is_string($translation['name'] ?? null)
            || ! is_string($translation['description'] ?? null)
            || ! is_array($translation['ui'] ?? null)) {
            throw new \LogicException(
                'Incomplete presented watch translation for locale '.$locale.'.'
            );
        }

        $copy = clone $watch;
        $copy->name = $translation['name'];
        $copy->description = $translation['description'];
        $copy->setAttribute(
            'short_description',
            is_string($translation['short_description'] ?? null)
                ? $translation['short_description']
                : $translation['description']
        );
        $copy->setAttribute('presented_copy', $translation['ui']);
        $copy->setAttribute('single_offer', true);

        return $copy;
    }
}
