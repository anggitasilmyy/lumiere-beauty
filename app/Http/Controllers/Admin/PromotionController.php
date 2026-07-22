<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipLevel;
use App\Models\Promotion;
use App\Models\Treatment;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::with(['minimumMembershipLevel', 'treatments'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('promo_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            }

            if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $promotions = $query->paginate(10)->withQueryString();

        $membershipLevels = MembershipLevel::orderBy('min_points')->get();

        $treatments = Treatment::where('is_active', true)
            ->orderBy('treatment_name')
            ->get();

        return view('admin.promotions.index', compact(
            'promotions',
            'membershipLevels',
            'treatments'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'discount_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'membership_only' => ['nullable', 'boolean'],
            'minimum_membership_level_id' => ['nullable', 'exists:membership_levels,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],

            // Relasi many-to-many Promotion ↔ Treatment
            'treatment_ids' => ['nullable', 'array'],
            'treatment_ids.*' => ['exists:treatments,id'],
        ]);

        $promotion = Promotion::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'promo_code' => $validated['promo_code'] ?? null,
            'discount_percent' => $validated['discount_percent'],
            'membership_only' => $request->boolean('membership_only'),
            'minimum_membership_level_id' => $validated['minimum_membership_level_id'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $promotion->treatments()->sync($validated['treatment_ids'] ?? []);

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion has been added successfully.');
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'discount_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'membership_only' => ['nullable', 'boolean'],
            'minimum_membership_level_id' => ['nullable', 'exists:membership_levels,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],

            // Relasi many-to-many Promotion ↔ Treatment
            'treatment_ids' => ['nullable', 'array'],
            'treatment_ids.*' => ['exists:treatments,id'],
        ]);

        $promotion->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'promo_code' => $validated['promo_code'] ?? null,
            'discount_percent' => $validated['discount_percent'],
            'membership_only' => $request->boolean('membership_only'),
            'minimum_membership_level_id' => $validated['minimum_membership_level_id'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $promotion->treatments()->sync($validated['treatment_ids'] ?? []);

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion has been updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->treatments()->detach();
        $promotion->delete();

        return redirect()
            ->route('admin.promotions.index')
            ->with('success', 'Promotion has been deleted successfully.');
    }
}