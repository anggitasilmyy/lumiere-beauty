<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_treatment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->cascadeOnDelete();
            $table->foreignId('treatment_id')->constrained('treatments')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['promotion_id', 'treatment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_treatment');
    }
};
