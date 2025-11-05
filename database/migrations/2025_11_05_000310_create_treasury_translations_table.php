<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treasury_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treasury_id');
            $table->string('locale', 5);
            $table->string('name');
            $table->timestamps();

            $table->unique(['treasury_id', 'locale']);
            $table->foreign('treasury_id')
                ->references('id')
                ->on('treasuries')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treasury_translations');
    }
};

