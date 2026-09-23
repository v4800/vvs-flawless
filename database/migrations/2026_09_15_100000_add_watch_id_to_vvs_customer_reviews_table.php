<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vvs_customer_reviews', function (Blueprint $table) {
            $table->foreignId('watch_id')
                ->nullable()
                ->after('id')
                ->constrained('watches')
                ->nullOnDelete();

            $table->index(
                ['watch_id', 'status', 'created_at'],
                'vvs_reviews_watch_status_created_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('vvs_customer_reviews', function (Blueprint $table) {
            $table->dropIndex('vvs_reviews_watch_status_created_index');
            $table->dropConstrainedForeignId('watch_id');
        });
    }
};
