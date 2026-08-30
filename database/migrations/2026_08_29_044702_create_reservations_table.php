<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('watch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('customer_name');
            $table->string('email');
            $table->string('phone');
            $table->string('city')->nullable();

            $table->string('delivery_method')
                ->default('Remise en main propre');

            $table->string('status')
                ->default('Nouvelle demande');

            $table->string('reservation_number')
                ->unique();

            $table->text('message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};