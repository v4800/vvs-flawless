<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('watches', function (Blueprint $table): void {
            $table->decimal('price', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::table('watches')->whereNull('price')->exists()) {
            throw new RuntimeException(
                'Impossible de rendre watches.price obligatoire : des prix restent a confirmer.'
            );
        }

        Schema::table('watches', function (Blueprint $table): void {
            $table->decimal('price', 8, 2)->nullable(false)->change();
        });
    }
};
