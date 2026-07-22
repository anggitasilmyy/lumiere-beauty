<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Services\PaymentChannelService;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request, PaymentChannelService $paymentChannelService)
    {
        $query = Treatment::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($subQuery) use ($request) {
                $subQuery->where('treatment_name', 'like', '%' . $request->search . '%')
                    ->orWhere('short_description', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $treatments = $query->latest()->paginate(9)->withQueryString();

        $categories = Treatment::where('is_active', true)
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $selectedTreatment = null;

        if ($request->filled('selected')) {
            $selectedTreatment = Treatment::where('is_active', true)
                ->find($request->selected);
        }

        $paymentMethods = $paymentChannelService->labels();

        return view('treatments.index', compact(
            'treatments',
            'categories',
            'selectedTreatment',
            'paymentMethods'
        ));
    }
}