<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->string('promo_code', 50)->nullable();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->boolean('membership_only')->default(false);

            $table->foreignId('minimum_membership_level_id')
                ->nullable()
                ->constrained('membership_levels')
                ->nullOnDelete();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};