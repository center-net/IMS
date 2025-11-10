<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            if (Schema::hasColumn('currencies', 'rate_to_usd')) {
                $table->dropColumn('rate_to_usd');
            }
        });
    }

    public function down(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            if (!Schema::hasColumn('currencies', 'rate_to_usd')) {
                $table->decimal('rate_to_usd', 18, 8)->default(1.00000000);
            }
        });
    }
};

