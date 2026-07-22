<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Promotion;
use App\Models\Treatment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTreatments = Treatment::count();

        $totalActiveTreatments = Treatment::where('is_active', true)->count();

        $totalCustomers = User::where('role', 'customer')->count();

        $totalBookings = Booking::count();

        $totalPendingBookings = Booking::where('booking_status', 'pending')->count();

        $totalWaitingPayments = Booking::where('payment_status', 'waiting_verification')->count();

        $totalCompletedTreatments = Booking::where('treatment_status', 'completed')->count();

        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');

        $totalPromotions = Promotion::count();

        $latestBookings = Booking::with(['user', 'treatment', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTreatments',
            'totalActiveTreatments',
            'totalCustomers',
            'totalBookings',
            'totalPendingBookings',
            'totalWaitingPayments',
            'totalCompletedTreatments',
            'totalRevenue',
            'totalPromotions',
            'latestBookings'
        ));
    }
}