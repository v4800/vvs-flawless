<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table): void {
            $table->decimal('deposit_amount', 10, 2)
                ->nullable()
                ->after('price');
        });

        DB::table('reservations')
            ->whereNotNull('price')
            ->whereNull('deposit_amount')
            ->update([
                'deposit_amount' => DB::raw('ROUND(price * 0.25, 2)'),
            ]);
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table): void {
            $table->dropColumn('deposit_amount');
        });
    }
};
