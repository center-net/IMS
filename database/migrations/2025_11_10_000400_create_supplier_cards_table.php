<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_cards', function (Blueprint $table) {
            $table->id();
            // Relations
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();
            // Contact
            $table->string('phone', 30)->nullable();
            $table->string('fax', 30)->nullable();
            // Registration / Tax
            $table->string('tax_number', 100)->nullable();
            $table->string('registration_number', 100)->nullable();
            // Type and status
            $table->enum('supplier_type', ['local', 'foreign'])->default('local');
            $table->enum('status', ['active', 'suspended'])->default('active');
            // Currency and credit
            $table->unsignedBigInteger('default_currency_id')->nullable();
            $table->decimal('credit_limit', 18, 2)->default(0);
            // Bank info
            $table->string('bank_name', 150)->nullable();
            $table->string('bank_account_number', 100)->nullable();
            $table->string('iban', 100)->nullable();
            $table->string('beneficiary_name', 150)->nullable();
            $table->unsignedBigInteger('bank_account_currency_id')->nullable();
            // Attachments
            $table->json('attachments')->nullable();
            // Creator
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('supplier_id')
                ->references('id')->on('suppliers')
                ->onDelete('cascade');

            $table->foreign('city_id')
                ->references('id')->on('cities')
                ->onDelete('set null');

            $table->foreign('village_id')
                ->references('id')->on('villages')
                ->onDelete('set null');

            $table->foreign('default_currency_id')
                ->references('id')->on('currencies')
                ->onDelete('set null');

            $table->foreign('bank_account_currency_id')
                ->references('id')->on('currencies')
                ->onDelete('set null');

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_cards');
    }
};

