<?php

namespace App\Support;

class LocalizedCopy
{
    public static function get(string $key, array $replace = []): mixed
    {
        $base = trans($key, $replace);
        $marketingKey = 'marketing.'.$key;
        $marketing = trans($marketingKey, $replace);

        if (is_array($base) && is_array($marketing)) {
            return array_replace_recursive($base, $marketing);
        }

        if (is_string($marketing) && $marketing !== $marketingKey) {
            return $marketing;
        }

        return $base;
    }

    /**
     * @return array<string, mixed>
     */
    public static function array(string $key): array
    {
        $value = self::get($key);

        return is_array($value) ? $value : [];
    }

    public static function string(string $key, array $replace = []): string
    {
        $value = self::get($key, $replace);

        return is_string($value) ? $value : '';
    }
}
