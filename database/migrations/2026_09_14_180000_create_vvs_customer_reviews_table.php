<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vvs_customer_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('display_name', 50);
            $table->unsignedTinyInteger('rating');
            $table->text('body');
            $table->string('status', 20)->default('pending')->index();
            $table->string('moderation_reason', 100)->nullable();
            $table->unsignedBigInteger('moderated_by')->nullable();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vvs_customer_reviews');
    }
};
