<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('reservations', 'deposit_amount_snapshot')) {
            Schema::table('reservations', function (Blueprint $table): void {
                $table->decimal('deposit_amount_snapshot', 10, 2)
                    ->nullable()
                    ->after('price');
            });
        }

        DB::table('reservations')
            ->select(['id', 'price'])
            ->whereNotNull('price')
            ->whereNull('deposit_amount_snapshot')
            ->orderBy('id')
            ->chunkById(
                100,
                function ($reservations): void {
                    foreach ($reservations as $reservation) {
                        $price = max(0.0, (float) $reservation->price);

                        DB::table('reservations')
                            ->where('id', $reservation->id)
                            ->update([
                                'deposit_amount_snapshot' => round(
                                    min(
                                        \App\Support\ReservationPayment::STANDARD_DEPOSIT_AMOUNT,
                                        $price
                                    ),
                                    2
                                ),
                            ]);
                    }
                }
            );
    }

    public function down(): void
    {
        if (Schema::hasColumn('reservations', 'deposit_amount_snapshot')) {
            Schema::table('reservations', function (Blueprint $table): void {
                $table->dropColumn('deposit_amount_snapshot');
            });
        }
    }
};