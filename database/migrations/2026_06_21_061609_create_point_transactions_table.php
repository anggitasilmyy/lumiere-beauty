<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Membuat tabel point_transactions untuk menyimpan riwayat transaksi poin user.
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();

            // Kolom user_id menghubungkan transaksi poin dengan user pemilik poin.
            $table->foreignId('user_id')
                ->constrained('users') // berarti terhubung ke tabel users.
                ->cascadeOnDelete(); // berarti jika user dihapus, transaksi poin miliknya ikut terhapus.

            // Kolom booking_id menghubungkan transaksi poin dengan booking tertentu.
            $table->foreignId('booking_id')
                ->nullable() // berarti transaksi poin boleh tidak berasal dari booking.
                ->constrained('bookings')
                ->nullOnDelete();// berarti jika booking dihapus, booking_id pada transaksi poin menjadi null.

            // Kolom points menyimpan jumlah poin pada transaksi.
            $table->integer('points')->default(0);

            // Kolom transaction_type menyimpan jenis transaksi poin.
            $table->enum('transaction_type', [
                'earn',
                'redeem',
                'adjustment',
                'expired',
            ])->default('earn');

            $table->string('description', 255)->nullable();
            $table->date('expired_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    // Method down dijalankan saat rollback migration. tabel point_transactions akan dihapus.
    public function down(): void
    {
        // Menghapus tabel point_transactions jika tabel tersebut ada.
        Schema::dropIfExists('point_transactions');
    }
};