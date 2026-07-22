<?php

namespace App\Services;

// Mengimport model Booking karena poin diberikan berdasarkan data booking.
use App\Models\Booking;

// Mengimport model MembershipLevel untuk menentukan level membership user.
use App\Models\MembershipLevel;

// Mengimport model PointTransaction untuk menghitung dan menyimpan riwayat poin.
use App\Models\PointTransaction;

// Mengimport model User untuk update total poin dan level membership user.
use App\Models\User;

// DB digunakan untuk menjalankan transaction agar proses database lebih aman.
use Illuminate\Support\Facades\DB;

class MembershipService
{
    // Menghitung total poin aktif milik user.
    public function getActivePoints(int $userId): int
    {
        return (int) PointTransaction::where('user_id', $userId)
            ->where(function ($query) {
                $query->whereNull('expired_at')

                    // Poin yang tanggal expired-nya masih hari ini atau setelah hari ini juga dihitung aktif.
                    ->orWhere('expired_at', '>=', now()->toDateString());
            })

            // Menjumlahkan seluruh poin aktif user.
            ->sum('points');
    }

    // Menghitung jumlah poin berdasarkan total harga booking.
    public function calculatePointsFromPrice(float $price): int
    {
        return max(1, (int) floor($price / 10000));
    }

    // Menyinkronkan total poin dan level membership user.
    // Method ini digunakan agar data user selalu sesuai dengan jumlah poin aktif terbaru.
    public function syncUserMembership(int $userId): void
    {
        // Mengambil data user berdasarkan ID.
        // Jika user tidak ditemukan, Laravel akan menampilkan error.
        $user = User::findOrFail($userId);

        // Menghitung total poin aktif user dari tabel point_transactions.
        $activePoints = $this->getActivePoints($userId);

        // Mencari level membership yang sesuai dengan total poin aktif user.
        // Sistem mengambil level tertinggi yang min_points-nya masih terpenuhi.
        $membershipLevel = MembershipLevel::where('min_points', '<=', $activePoints)
            ->orderByDesc('min_points')
            ->first();

        // Update total poin dan level membership user di tabel users.
        $user->update([
            'total_points' => $activePoints,

            // Jika level tidak ditemukan, default-nya menggunakan ID 1 yaitu Bronze.
            'membership_level_id' => $membershipLevel?->id ?? 1,
        ]);
    }

    // Memberikan poin kepada user berdasarkan booking yang sudah paid.
    public function awardPointsForBooking(int $bookingId): int
    {
        // DB transaction digunakan agar proses pemberian poin berjalan aman.
        // Jika ada error di tengah proses, perubahan database akan dibatalkan.
        return DB::transaction(function () use ($bookingId) {
            // Mengambil data booking beserta relasi user dan payment.
            $booking = Booking::with(['user', 'payment'])->findOrFail($bookingId);

            // Poin hanya diberikan jika status pembayaran sudah paid.
            // Jika belum paid, sistem tidak memberikan poin.
            if ($booking->payment_status !== 'paid') {
                return 0;
            }

            // Mengecek apakah booking ini sudah pernah mendapatkan poin.
            // Tujuannya untuk mencegah poin dobel dari booking yang sama.
            $alreadyAwarded = PointTransaction::where('booking_id', $booking->id)
                ->where('transaction_type', 'earn')
                ->exists();

            // Jika poin sudah pernah diberikan, proses dihentikan.
            if ($alreadyAwarded) {
                return 0;
            }

            // Menghitung poin berdasarkan total harga booking.
            $points = $this->calculatePointsFromPrice((float) $booking->total_price);

            // Menyimpan transaksi poin baru ke tabel point_transactions.
            PointTransaction::create([
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'points' => $points,
                'transaction_type' => 'earn',
                'description' => 'Poin dari booking ' . $booking->booking_code,

                // Poin diberi masa berlaku selama 1 tahun.
                'expired_at' => now()->addYear()->toDateString(),

                // Menyimpan waktu transaksi poin dibuat.
                'created_at' => now(),
            ]);

            // Setelah poin ditambahkan, total poin dan level membership user diperbarui.
            $this->syncUserMembership($booking->user_id);

            // Mengembalikan jumlah poin yang berhasil diberikan.
            return $points;
        });
    }
}