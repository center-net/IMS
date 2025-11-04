<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->string('locale', 5);
            $table->string('display_name');
            $table->timestamps();

            $table->unique(['permission_id', 'locale']);
            $table->foreign('permission_id')
                ->references('id')
                ->on(config('permission.table_names.permissions', 'permissions'))
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_translations');
    }
};

