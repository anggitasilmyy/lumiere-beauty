<?php

namespace App\Http\Controllers;

// Mengimport model PointTransaction untuk mengambil riwayat transaksi poin user.
use App\Models\PointTransaction;

// Mengimport MembershipService untuk menyinkronkan poin dan level membership user.
use App\Services\MembershipService;

class PointController extends Controller
{
    // Menampilkan halaman My Points untuk user yang sedang login.
    public function index(MembershipService $membershipService)
    {
        // Mengambil data user yang sedang login beserta level membership-nya.
        $user = auth()->user()->load('membershipLevel');

        // Menyinkronkan total poin dan level membership user. poin dan level yang tampil selalu terbaru.
        $membershipService->syncUserMembership($user->id);

        // Mengambil ulang data user setelah proses sinkronisasi. karena total_points dan membership_level_id bisa saja berubah.
        $user->refresh()->load('membershipLevel');

        // Mengambil riwayat transaksi poin milik user.
        $pointTransactions = PointTransaction::with('booking')
            ->where('user_id', $user->id)
            ->latest('created_at') // mengurutkan data dari transaksi terbaru.
            ->paginate(10);// membatasi tampilan 10 transaksi per halaman.

        // Mengirim data user dan riwayat poin ke halaman My Points.
        return view('points.index', compact('user', 'pointTransactions'));
    }
}