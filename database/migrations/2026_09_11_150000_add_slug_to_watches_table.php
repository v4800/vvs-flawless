<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('watches', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        $usedSlugs = [];

        DB::table('watches')
            ->select(['id', 'name'])
            ->orderBy('id')
            ->get()
            ->each(function (object $watch) use (&$usedSlugs): void {
                $baseSlug = Str::slug((string) $watch->name);

                if ($baseSlug === '') {
                    $baseSlug = 'watch-'.$watch->id;
                }

                $slug = $baseSlug;
                $suffix = 2;

                while (isset($usedSlugs[$slug])) {
                    $slug = $baseSlug.'-'.$suffix;
                    $suffix++;
                }

                $usedSlugs[$slug] = true;

                DB::table('watches')
                    ->where('id', $watch->id)
                    ->update(['slug' => $slug]);
            });

        Schema::table('watches', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('watches', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
