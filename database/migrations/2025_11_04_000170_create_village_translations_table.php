<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('village_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('village_id');
            $table->string('locale', 5);
            $table->string('name');
            $table->timestamps();

            $table->unique(['village_id', 'locale']);
            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('village_translations');
    }
};

