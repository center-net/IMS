<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('representatives', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('representative_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('representative_id')->constrained('representatives')->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
            $table->unique(['representative_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('representative_translations');
        Schema::dropIfExists('representatives');
    }
};

