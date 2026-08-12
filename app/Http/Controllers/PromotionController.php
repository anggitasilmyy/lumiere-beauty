<?php

namespace App\Http\Controllers;

use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user) {
            $user->loadMissing('membershipLevel');
        }

        $promotions = Promotion::with([
            'minimumMembershipLevel',
            'treatments',
        ])
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
            ->paginate(9)
            ->withQueryString();

        return view('promotions.index', compact('promotions', 'user'));
    }
}