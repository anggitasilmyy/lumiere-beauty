<?php

namespace App\Http\Controllers\Admin;

// Mengimport Controller utama Laravel.
use App\Http\Controllers\Controller;

// Mengimport model Booking untuk mengambil dan mengubah data booking.
use App\Models\Booking;

// Mengimport MembershipService untuk memberikan poin setelah pembayaran menjadi paid.
use App\Services\MembershipService;

// Request digunakan untuk mengambil data filter dan input status dari admin.
use Illuminate\Http\Request;

// DB digunakan untuk menjalankan transaction agar proses update lebih aman.
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // Menampilkan daftar booking di halaman admin.
    public function index(Request $request)
    {
        // Mengambil data booking beserta relasi user, treatment, dan payment.
        // Relasi ini dibutuhkan agar admin bisa melihat detail customer, treatment, dan pembayaran.
        $query = Booking::with(['user', 'treatment', 'payment']);

        // Filter booking berdasarkan status pembayaran jika admin memilih filter payment_status.
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter booking berdasarkan status booking jika admin memilih filter booking_status.
        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->booking_status);
        }

        // Filter booking berdasarkan status treatment jika admin memilih filter treatment_status.
        if ($request->filled('treatment_status')) {
            $query->where('treatment_status', $request->treatment_status);
        }

        // Fitur pencarian booking.
        // Admin bisa mencari berdasarkan kode booking, nama/email user, atau nama treatment.
        if ($request->filled('search')) {
            $query->where(function ($subQuery) use ($request) {
                // Mencari berdasarkan booking_code.
                $subQuery->where('booking_code', 'like', '%' . $request->search . '%')

                    // Mencari berdasarkan nama atau email customer dari relasi user.
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                    })

                    // Mencari berdasarkan nama treatment dari relasi treatment.
                    ->orWhereHas('treatment', function ($treatmentQuery) use ($request) {
                        $treatmentQuery->where('treatment_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Mengurutkan booking dari yang terbaru.
        // paginate(10) membatasi tampilan menjadi 10 data per halaman.
        // withQueryString() menjaga filter/search tetap aktif saat pindah halaman pagination.
        $bookings = $query->latest()->paginate(10)->withQueryString();

        // Mengirim data booking ke halaman admin bookings.
        return view('admin.bookings.index', compact('bookings'));
    }

    // Mengubah status pembayaran, status booking, dan status treatment oleh admin.
    public function updateStatus(Request $request, Booking $booking, MembershipService $membershipService)
    {
        // Validasi input status dari form admin.
        // Nilai status dibatasi agar database tidak menerima status sembarangan.
        $data = $request->validate([
            'payment_status' => ['required', 'in:unpaid,waiting_verification,paid,failed,refunded'],
            'booking_status' => ['required', 'in:pending,confirmed,cancelled'],
            'treatment_status' => ['required', 'in:not_started,scheduled,completed,cancelled'],
        ]);

        // Transaction digunakan agar update booking, payment, dan pemberian poin berjalan aman.
        // Jika ada error di salah satu proses, perubahan database akan dibatalkan.
        DB::transaction(function () use ($booking, $data, $membershipService) {
            // Jika pembayaran sudah paid, maka booking otomatis dikonfirmasi.
            if ($data['payment_status'] === 'paid') {
                // Jika status booking masih pending, ubah menjadi confirmed.
                if ($data['booking_status'] === 'pending') {
                    $data['booking_status'] = 'confirmed';
                }

                // Jika treatment belum dimulai, ubah menjadi scheduled.
                if ($data['treatment_status'] === 'not_started') {
                    $data['treatment_status'] = 'scheduled';
                }
            }

            // Jika pembayaran gagal atau refund,
            // maka booking dan treatment otomatis dibatalkan.
            if (in_array($data['payment_status'], ['failed', 'refunded'], true)) {
                $data['booking_status'] = 'cancelled';
                $data['treatment_status'] = 'cancelled';
            }

            // Update status pada tabel bookings.
            $booking->update($data);

            // Jika booking memiliki data payment, maka status payment juga ikut diperbarui.
            if ($booking->payment) {
                $booking->payment->update([
                    // Menyamakan status pembayaran di tabel payments dengan tabel bookings.
                    'payment_status' => $data['payment_status'],

                    // Jika payment paid, sistem membuat receipt_code jika belum ada.
                    // Jika bukan paid, receipt_code tetap menggunakan nilai sebelumnya.
                    'receipt_code' => $data['payment_status'] === 'paid'
                        ? ($booking->payment->receipt_code ?? $this->generateReceiptCode())
                        : $booking->payment->receipt_code,

                    // Jika payment paid, paid_at diisi waktu sekarang jika sebelumnya belum ada.
                    // Jika bukan paid, paid_at dikosongkan.
                    'paid_at' => $data['payment_status'] === 'paid'
                        ? ($booking->payment->paid_at ?? now())
                        : null,

                    // Jika payment paid, confirmed_at diisi waktu admin melakukan konfirmasi.
                    // Jika bukan paid, confirmed_at dikosongkan.
                    'confirmed_at' => $data['payment_status'] === 'paid'
                        ? now()
                        : null,
                ]);
            }

            // Jika pembayaran sudah paid, sistem memberikan poin kepada customer.
            // Proses pemberian poin dilakukan melalui MembershipService.
            if ($data['payment_status'] === 'paid') {
                $membershipService->awardPointsForBooking($booking->id);
            }
        });

        // Setelah status berhasil diperbarui, admin diarahkan kembali ke halaman daftar booking.
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Status booking berhasil diperbarui.');
    }

    // Membuat kode receipt otomatis untuk pembayaran yang sudah paid.
    private function generateReceiptCode(): string
    {
        // Format kode receipt:
        // RCPT-tanggaljam-random angka
        // Contoh: RCPT-20260629123045-1234
        return 'RCPT-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
    }
}