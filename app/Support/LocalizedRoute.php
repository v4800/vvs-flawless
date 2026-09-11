<?php

namespace App\Support;

final class LocalizedRoute
{
    public function name(string $name): string
    {
        return match (app()->getLocale()) {
            'nl_BE' => 'nl.'.$name,
            'en_BE' => 'en.'.$name,
            default => $name,
        };
    }
}
