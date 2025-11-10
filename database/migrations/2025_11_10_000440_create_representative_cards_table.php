<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('representative_cards')) {
            Schema::create('representative_cards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('representative_id')->constrained('representatives')->cascadeOnDelete();
                $table->string('code')->nullable()->unique(); // e.g., SAL001
                $table->string('role')->nullable(); // Sales / Purchases / Marketing
                $table->string('branch')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->decimal('commission_rate', 8, 2)->nullable();
                $table->enum('commission_method', ['gross_sales', 'profit', 'after_collection'])->nullable();
                $table->decimal('commission_min', 12, 2)->nullable();
                $table->decimal('commission_max', 12, 2)->nullable();
                $table->enum('status', ['active', 'suspended'])->default('active');
                $table->json('attachments')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('representative_card_translations')) {
            Schema::create('representative_card_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('representative_card_id')->constrained('representative_cards')->cascadeOnDelete();
                $table->string('locale')->index();
                $table->string('name')->nullable(); // Card display name
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->unique(['representative_card_id', 'locale']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('representative_card_translations')) {
            Schema::dropIfExists('representative_card_translations');
        }
        if (Schema::hasTable('representative_cards')) {
            Schema::dropIfExists('representative_cards');
        }
    }
};
