<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treasuries', function (Blueprint $table) {
            // Store the main treasury linkage: 0 or NULL for main treasuries, main ID for sub-treasuries
            $table->unsignedBigInteger('main_treasury_id')->nullable()->after('is_main');
        });
    }

    public function down(): void
    {
        Schema::table('treasuries', function (Blueprint $table) {
            $table->dropColumn('main_treasury_id');
        });
    }
};

