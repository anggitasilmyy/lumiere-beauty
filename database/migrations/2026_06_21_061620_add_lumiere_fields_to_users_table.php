<?php

// Migration digunakan untuk membuat atau mengubah struktur tabel database.
use Illuminate\Database\Migrations\Migration;

// Blueprint digunakan untuk mendefinisikan kolom-kolom pada tabel.
use Illuminate\Database\Schema\Blueprint;

// Schema digunakan untuk menjalankan perubahan struktur tabel.
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Method up dijalankan saat perintah php artisan migrate dilakukan.
    // Pada bagian ini sistem menambahkan beberapa kolom baru ke tabel users.
    public function up(): void
    {
        // Mengubah struktur tabel users.
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom phone untuk menyimpan nomor telepon customer.
            // Nullable berarti nomor telepon boleh kosong.
            // after('email') berarti kolom phone diletakkan setelah kolom email.
            $table->string('phone', 30)->nullable()->after('email');

            // Menambahkan kolom role untuk membedakan customer dan admin.
            // Default-nya customer, sehingga user baru otomatis menjadi customer.
            $table->enum('role', ['customer', 'admin'])->default('customer')->after('password');

            // Menambahkan kolom membership_level_id sebagai foreign key ke tabel membership_levels.
            // Kolom ini menentukan level membership user, misalnya Bronze, Silver, Gold, atau Platinum.
            $table->foreignId('membership_level_id')
                ->default(1)

                // Kolom membership_level_id diletakkan setelah kolom role.
                ->after('role')

                // Menghubungkan membership_level_id ke id pada tabel membership_levels.
                ->constrained('membership_levels')

                // restrictOnDelete mencegah level membership dihapus jika masih digunakan oleh user.
                ->restrictOnDelete();

            // Menambahkan kolom total_points untuk menyimpan total poin aktif user.
            // Default 0 berarti user baru belum memiliki poin.
            $table->integer('total_points')->default(0)->after('membership_level_id');

            // Menambahkan kolom is_active untuk menandai apakah akun user aktif atau tidak.
            // Default true berarti akun user aktif sejak dibuat.
            $table->boolean('is_active')->default(true)->after('total_points');
        });
    }

    // Method down dijalankan saat rollback migration.
    // Pada bagian ini kolom-kolom tambahan akan dihapus dari tabel users.
    public function down(): void
    {
        // Mengubah struktur tabel users untuk menghapus kolom yang sebelumnya ditambahkan.
        Schema::table('users', function (Blueprint $table) {
            // Menghapus foreign key membership_level_id terlebih dahulu.
            // Foreign key harus dihapus sebelum kolomnya dihapus.
            $table->dropForeign(['membership_level_id']);

            // Menghapus kolom tambahan dari tabel users.
            $table->dropColumn([
                'phone',
                'role',
                'membership_level_id',
                'total_points',
                'is_active',
            ]);
        });
    }
};