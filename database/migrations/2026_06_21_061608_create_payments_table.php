<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->unique()
                ->constrained('bookings')
                ->cascadeOnDelete();

            $table->string('transaction_code', 80)->unique();
            $table->string('receipt_code', 80)->nullable()->unique();

            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'e_wallet',
                'qris',
            ])->default('qris');

            $table->decimal('amount', 12, 2)->default(0);

            $table->enum('payment_status', [
                'unpaid',
                'waiting_verification',
                'paid',
                'failed',
                'refunded',
            ])->default('unpaid');

            $table->string('payer_name', 100)->nullable();
            $table->string('payment_reference', 100)->nullable();
            $table->string('payment_proof')->nullable();
            $table->text('payment_note')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};