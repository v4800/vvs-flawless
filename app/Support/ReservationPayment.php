<?php

namespace App\Support;

final class ReservationPayment
{
    public const DEPOSIT_AMOUNT = 100.00;

    public static function depositAmount(float|int|string|null $price): ?float
    {
        if ($price === null) {
            return null;
        }

        $normalizedPrice = max(0.0, (float) $price);

        return round(min(self::DEPOSIT_AMOUNT, $normalizedPrice), 2);
    }

    public static function balanceAmount(float|int|string|null $price): ?float
    {
        if ($price === null) {
            return null;
        }

        $normalizedPrice = max(0.0, (float) $price);
        $deposit = self::depositAmount($normalizedPrice) ?? 0.0;

        return round(max(0.0, $normalizedPrice - $deposit), 2);
    }

    public static function confirmedPaidAmount(
        float|int|string|null $price,
        bool $depositPaid,
        bool $balancePaid
    ): float {
        $amount = 0.0;

        if ($depositPaid) {
            $amount += self::depositAmount($price) ?? 0.0;
        }

        if ($balancePaid) {
            $amount += self::balanceAmount($price) ?? 0.0;
        }

        return round($amount, 2);
    }
}
