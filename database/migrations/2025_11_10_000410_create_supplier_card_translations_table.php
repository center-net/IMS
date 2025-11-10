<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_card_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_card_id');
            $table->string('locale', 5);
            $table->string('name'); // اسم بطاقة المورد
            $table->string('trade_name')->nullable(); // الاسم التجاري
            $table->text('notes')->nullable(); // ملاحظات عامة
            $table->timestamps();

            $table->unique(['supplier_card_id', 'locale']);
            $table->foreign('supplier_card_id')
                ->references('id')
                ->on('supplier_cards')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_card_translations');
    }
};

