<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('reservations', 'utm_source')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('utm_source')->nullable();
            });
        }

        if (! Schema::hasColumn('reservations', 'utm_medium')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('utm_medium')->nullable();
            });
        }

        if (! Schema::hasColumn('reservations', 'utm_campaign')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('utm_campaign')->nullable();
            });
        }

        if (! Schema::hasColumn('reservations', 'utm_term')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('utm_term')->nullable();
            });
        }

        if (! Schema::hasColumn('reservations', 'utm_content')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('utm_content')->nullable();
            });
        }

        if (! Schema::hasColumn('reservations', 'referrer')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->text('referrer')->nullable();
            });
        }

        if (! Schema::hasColumn('reservations', 'landing_page')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->text('landing_page')->nullable();
            });
        }
    }

    public function down(): void
    {
        $columns = [
            'utm_term',
            'utm_content',
            'referrer',
            'landing_page',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('reservations', $column)) {
                Schema::table('reservations', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
