<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiscal_month_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fiscal_month_id');
            $table->string('locale', 5);
            $table->string('name');
            $table->timestamps();

            $table->unique(['fiscal_month_id', 'locale']);
            $table->foreign('fiscal_month_id')
                ->references('id')
                ->on('fiscal_months')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_month_translations');
    }
};

