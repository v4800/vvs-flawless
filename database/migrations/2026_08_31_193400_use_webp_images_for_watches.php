<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $watches = DB::table('watches')
            ->select([
                'id',
                'image',
            ])
            ->get();

        foreach ($watches as $watch) {
            if (
                ! is_string($watch->image)
                || ! str_ends_with($watch->image, '.png')
            ) {
                continue;
            }

            DB::table('watches')
                ->where('id', $watch->id)
                ->update([
                    'image' => substr(
                        $watch->image,
                        0,
                        -4
                    ).'.webp',
                ]);
        }
    }

    public function down(): void
    {
        $watches = DB::table('watches')
            ->select([
                'id',
                'image',
            ])
            ->get();

        foreach ($watches as $watch) {
            if (
                ! is_string($watch->image)
                || ! str_ends_with($watch->image, '.webp')
            ) {
                continue;
            }

            DB::table('watches')
                ->where('id', $watch->id)
                ->update([
                    'image' => substr(
                        $watch->image,
                        0,
                        -5
                    ).'.png',
                ]);
        }
    }
};
