<?php

namespace App\Http\Controllers;

// Mengimport model Booking untuk mengambil data booking customer dari database.
use App\Models\Booking;

class BookingController extends Controller
{
    // Menampilkan halaman My Bookings untuk user yang sedang login.
    public function myBookings()
    {
        // Mengambil data booking milik user yang sedang login.
        // with() digunakan untuk mengambil data relasi treatment, payment, dan reviews.
        $bookings = Booking::with(['treatment', 'payment', 'reviews'])

            // Membatasi data agar user hanya bisa melihat booking miliknya sendiri.
            ->where('user_id', auth()->id())

            // Mengurutkan data booking dari yang terbaru.
            ->latest()

            // Membatasi tampilan menjadi 5 data per halaman agar daftar tidak terlalu panjang.
            ->paginate(5)

            // Menjaga query string saat berpindah halaman.
            ->withQueryString();

        // Mengirim data booking ke halaman My Bookings.
        return view('bookings.my-bookings', compact('bookings'));
    }
}