<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 50)->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('treatment_id')
                ->constrained('treatments')
                ->cascadeOnDelete();

            $table->date('booking_date');
            $table->time('booking_time');
            $table->text('notes')->nullable();
            $table->decimal('total_price', 12, 2)->default(0);

            $table->enum('payment_status', [
                'unpaid',
                'waiting_verification',
                'paid',
                'failed',
                'refunded',
            ])->default('unpaid');

            $table->enum('booking_status', [
                'pending',
                'confirmed',
                'cancelled',
            ])->default('pending');

            $table->enum('treatment_status', [
                'not_started',
                'scheduled',
                'completed',
                'cancelled',
            ])->default('not_started');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};