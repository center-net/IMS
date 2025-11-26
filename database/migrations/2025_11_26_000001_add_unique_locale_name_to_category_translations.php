<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $dups = DB::table('category_translations')
            ->select('locale', 'name', DB::raw('COUNT(*) as c'))
            ->groupBy('locale', 'name')
            ->having('c', '>', 1)
            ->get();

        foreach ($dups as $row) {
            $others = DB::table('category_translations')
                ->where('locale', $row->locale)
                ->where('name', $row->name)
                ->orderBy('id')
                ->skip(1)
                ->take(1000000)
                ->get(['id', 'name']);
            foreach ($others as $dup) {
                $newName = $dup->name . '-' . $dup->id;
                DB::table('category_translations')
                    ->where('id', $dup->id)
                    ->update(['name' => $newName]);
            }
        }
        Schema::table('category_translations', function (Blueprint $table) {
            $table->unique(['locale', 'name'], 'category_translations_locale_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropUnique('category_translations_locale_name_unique');
        });
    }
};
