<?php

namespace App\Http\Controllers;

use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $promotions = Promotion::with('minimumMembershipLevel')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now()->toDateString());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->latest()
            ->paginate(9);

        return view('promotions.index', compact('promotions', 'user'));
    }
}