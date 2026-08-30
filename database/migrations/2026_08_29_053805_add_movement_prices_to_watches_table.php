<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('watches', function (Blueprint $table) {
            $table->decimal('japanese_price', 8, 2)->nullable();
            $table->decimal('japanese_promo_price', 8, 2)->nullable();

            $table->decimal('swiss_price', 8, 2)->nullable();
            $table->decimal('swiss_promo_price', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('watches', function (Blueprint $table) {
            $table->dropColumn([
                'japanese_price',
                'japanese_promo_price',
                'swiss_price',
                'swiss_promo_price',
            ]);
        });
    }
};