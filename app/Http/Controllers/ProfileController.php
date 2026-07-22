<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('membershipLevel'); //digunakan untuk mengambil data level membership user.

        // Menghitung seluruh booking milik user yang sedang login.
        $totalBookings = Booking::where('user_id', $user->id)->count();

        // Menghitung jumlah booking user yang pembayarannya sudah paid.
        $paidBookings = Booking::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->count();

        // Menghitung jumlah treatment user yang statusnya sudah completed.
        $completedTreatments = Booking::where('user_id', $user->id)
            ->where('treatment_status', 'completed')
            ->count();

        // Mengirim data user dan statistik booking ke halaman profile.
        return view('profile.index', compact(
            'user',
            'totalBookings',
            'paidBookings',
            'completedTreatments'
        ));
    }
}