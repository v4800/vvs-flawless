<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('watch_name_snapshot')->nullable();
            $table->text('watch_image_snapshot')->nullable();
            $table->dateTime('deposit_paid_at')->nullable()->index();
            $table->dateTime('balance_paid_at')->nullable()->index();
            $table->dateTime('appointment_at')->nullable()->index();
            $table->string('handover_address', 500)->nullable();
            $table->decimal('travel_fee', 10, 2)->default(0);
            $table->text('admin_notes')->nullable();
            $table->boolean('is_test')->default(false)->index();
            $table->dateTime('archived_at')->nullable()->index();
            $table->index('status');
        });

        Schema::create('reservation_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['reservation_id', 'created_at']);
        });

        DB::table('reservations')
            ->select(['id', 'watch_id'])
            ->orderBy('id')
            ->chunkById(100, function ($reservations): void {
                foreach ($reservations as $reservation) {
                    $watch = DB::table('watches')
                        ->where('id', $reservation->watch_id)
                        ->first(['name', 'image']);

                    if ($watch === null) {
                        continue;
                    }

                    DB::table('reservations')
                        ->where('id', $reservation->id)
                        ->update([
                            'watch_name_snapshot' => $watch->name,
                            'watch_image_snapshot' => $watch->image,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_status_histories');

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['deposit_paid_at']);
            $table->dropIndex(['balance_paid_at']);
            $table->dropIndex(['appointment_at']);
            $table->dropIndex(['is_test']);
            $table->dropIndex(['archived_at']);
            $table->dropIndex(['status']);

            $table->dropColumn([
                'watch_name_snapshot',
                'watch_image_snapshot',
                'deposit_paid_at',
                'balance_paid_at',
                'appointment_at',
                'handover_address',
                'travel_fee',
                'admin_notes',
                'is_test',
                'archived_at',
            ]);
        });
    }
};
