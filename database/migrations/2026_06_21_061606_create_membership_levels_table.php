<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Method up dijalankan saat perintah php artisan migrate dilakukan. membuat tabel membership_levels.
    public function up(): void
    {
        // Membuat tabel membership_levels untuk menyimpan data level membership.
        Schema::create('membership_levels', function (Blueprint $table) {
            $table->id();
            $table->string('level_name', 50);
            $table->integer('min_points')->default(0);
            $table->text('benefits')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    // Method down dijalankan saat rollback migration. tabel membership_levels akan dihapus.
    public function down(): void
    {
        // Menghapus tabel membership_levels jika tabel tersebut ada.
        Schema::dropIfExists('membership_levels');
    }
};