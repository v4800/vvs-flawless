<?php

namespace App\Support;

final class ReservationWorkflow
{
    public const NEW = 'Nouvelle demande';

    public const CONTACTED = 'Client contacté';

    public const DEPOSIT_PAID = 'Acompte de 100 € reçu';

    public const PREPARING = 'Montre commandée / en préparation';

    public const VIDEO_SENT = 'Vidéo de la montre envoyée';

    public const APPOINTMENT = 'Rendez-vous planifié';

    public const COMPLETED = 'Remise effectuée et solde payé';

    public const CANCELLED = 'Annulée';

    /**
     * @return list<string>
     */
    public static function statuses(): array
    {
        return [
            self::NEW,
            self::CONTACTED,
            self::DEPOSIT_PAID,
            self::PREPARING,
            self::VIDEO_SENT,
            self::APPOINTMENT,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }

    /**
     * Legacy values remain valid so historical reservations are never rewritten.
     *
     * @return list<string>
     */
    public static function acceptedStatuses(): array
    {
        return array_values(array_unique([
            ...self::statuses(),
            'Acompte de 25 % reçu',
            'Confirmée',
            'Commandée',
            'Disponible',
            'Terminée',
        ]));
    }

    /**
     * @return array<string, list<string>>
     */
    public static function statisticGroups(): array
    {
        return [
            'new' => [self::NEW],
            'contacted' => [self::CONTACTED, 'Confirmée'],
            'deposit_paid' => [self::DEPOSIT_PAID, 'Acompte de 25 % reçu'],
            'preparing' => [self::PREPARING, self::VIDEO_SENT, 'Commandée', 'Disponible'],
            'appointment' => [self::APPOINTMENT],
            'completed' => [self::COMPLETED, 'Terminée'],
            'cancelled' => [self::CANCELLED],
        ];
    }

    public static function isCompleted(?string $status): bool
    {
        return in_array($status, [self::COMPLETED, 'Terminée'], true);
    }
}
