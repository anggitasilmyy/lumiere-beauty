<?php
namespace App\Http\Controllers;
use App\Models\Promotion;
use App\Models\Treatment;
class HomeController extends Controller
{
    public function index()
    {
        $featuredTreatments = Treatment::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $promotions = Promotion::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now()->toDateString());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('featuredTreatments', 'promotions'));
    }
}