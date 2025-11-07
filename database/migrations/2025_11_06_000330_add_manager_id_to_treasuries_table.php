<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treasuries', function (Blueprint $table) {
            // Link treasury to a manager (employee). Allow NULL when no manager assigned.
            $table->unsignedBigInteger('manager_id')->nullable()->after('main_treasury_id');
            // Ensure an employee cannot manage more than one treasury
            $table->unique('manager_id');
            $table->foreign('manager_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('treasuries', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropUnique(['manager_id']);
            $table->dropColumn('manager_id');
        });
    }
};

