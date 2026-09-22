<?php

namespace App\Support;

final class ReservationPayment
{
    public const DEPOSIT_AMOUNT = 100.00;

    public static function depositAmount(
        float|int|string|null $price,
        float|int|string|null $storedDeposit = null
    ): ?float {
        if ($price === null) {
            return null;
        }

        $normalizedPrice = max(0.0, (float) $price);

        if ($storedDeposit !== null) {
            return round(
                min(max(0.0, (float) $storedDeposit), $normalizedPrice),
                2
            );
        }

        return round(min(self::DEPOSIT_AMOUNT, $normalizedPrice), 2);
    }

    public static function balanceAmount(
        float|int|string|null $price,
        float|int|string|null $storedDeposit = null
    ): ?float {
        if ($price === null) {
            return null;
        }

        $normalizedPrice = max(0.0, (float) $price);
        $deposit = self::depositAmount(
            $normalizedPrice,
            $storedDeposit
        ) ?? 0.0;

        return round(max(0.0, $normalizedPrice - $deposit), 2);
    }

    public static function confirmedPaidAmount(
        float|int|string|null $price,
        bool $depositPaid,
        bool $balancePaid,
        float|int|string|null $storedDeposit = null
    ): float {
        $amount = 0.0;

        if ($depositPaid) {
            $amount += self::depositAmount(
                $price,
                $storedDeposit
            ) ?? 0.0;
        }

        if ($balancePaid) {
            $amount += self::balanceAmount(
                $price,
                $storedDeposit
            ) ?? 0.0;
        }

        return round($amount, 2);
    }
}
