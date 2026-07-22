<?php

namespace App\Http\Controllers;

// Mengimport model Booking karena review dapat diberikan untuk booking.
use App\Models\Booking;

// Mengimport model Promotion karena sistem review juga mendukung review untuk promotion.
use App\Models\Promotion;

// Mengimport model Review untuk menyimpan atau memperbarui data review user.
use App\Models\Review;

// Mengimport model Treatment karena sistem review juga mendukung review untuk treatment.
use App\Models\Treatment;

// Request digunakan untuk mengambil dan memvalidasi data dari form review.
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Method store digunakan untuk menyimpan atau memperbarui review user.
    public function store(Request $request)
    {
        // Validasi data review yang dikirim dari form.
        // reviewable_type menentukan objek yang direview: booking, treatment, atau promotion.
        // rating wajib berupa angka 1 sampai 5.
        // comment boleh kosong, tetapi maksimal 1000 karakter jika diisi.
        $validated = $request->validate([
            'reviewable_type' => ['required', 'in:booking,treatment,promotion'],
            'reviewable_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Mapping tipe review dari input form ke model Laravel yang sesuai.
        // Contoh: jika reviewable_type adalah booking, maka model yang dipakai adalah Booking::class.
        $typeMap = [
            'booking' => Booking::class,
            'treatment' => Treatment::class,
            'promotion' => Promotion::class,
        ];

        // Mengambil class model berdasarkan tipe review yang dipilih.
        $modelClass = $typeMap[$validated['reviewable_type']];

        // Mencari data yang akan direview berdasarkan ID.
        // Jika data tidak ditemukan, Laravel akan menampilkan error 404.
        $reviewable = $modelClass::findOrFail($validated['reviewable_id']);

        // Jika objek yang direview adalah booking, maka ada aturan tambahan.
        if ($reviewable instanceof Booking) {
            // Mengecek apakah booking tersebut milik user yang sedang login.
            // Ini mencegah user memberi review pada booking milik orang lain.
            if ($reviewable->user_id !== auth()->id()) {
                abort(403, 'You are not allowed to review this booking.');
            }

            // Review hanya boleh diberikan jika treatment sudah selesai.
            if ($reviewable->treatment_status !== 'completed') {
                return back()->with('error', 'Review can only be added after treatment is completed.');
            }
        }

        // Menyimpan review baru atau memperbarui review lama.
        // Jika user sudah pernah memberi review pada objek yang sama, maka review akan di-update.
        // Jika belum pernah, maka review baru akan dibuat.
        Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'reviewable_id' => $reviewable->id,
                'reviewable_type' => $modelClass,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        // Setelah review berhasil disimpan, user dikembalikan ke halaman sebelumnya.
        return back()->with('success', 'Review has been saved successfully.');
    }
}