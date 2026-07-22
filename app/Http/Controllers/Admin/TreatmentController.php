<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use App\Services\TreatmentImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Treatment::query();

        if ($request->filled('search')) {
            $query->where(function ($subQuery) use ($request) {
                $subQuery->where('treatment_name', 'like', '%' . $request->search . '%')
                    ->orWhere('category', 'like', '%' . $request->search . '%')
                    ->orWhere('short_description', 'like', '%' . $request->search . '%');
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

        $treatments = $query->latest()->paginate(10)->withQueryString();

        return view('admin.treatments.index', compact('treatments'));
    }

    public function create()
    {
        return view('admin.treatments.create');
    }

    public function store(Request $request, TreatmentImageService $imageService)
    {
        $data = $request->validate([
            'treatment_name' => ['required', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = $this->uniqueSlug($data['treatment_name']);

        $imagePath = $imageService->upload($request->file('image'));

        Treatment::create([
            'treatment_name' => $data['treatment_name'],
            'slug' => $slug,
            'category' => $data['category'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'image' => $imagePath,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.treatments.index')
            ->with('success', 'Treatment berhasil ditambahkan.');
    }

    public function show(Treatment $treatment)
    {
        return redirect()->route('admin.treatments.edit', $treatment);
    }

    public function edit(Treatment $treatment)
    {
        return view('admin.treatments.edit', compact('treatment'));
    }

    public function update(Request $request, Treatment $treatment, TreatmentImageService $imageService)
    {
        $data = $request->validate([
            'treatment_name' => ['required', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = $treatment->slug;

        if ($treatment->treatment_name !== $data['treatment_name']) {
            $slug = $this->uniqueSlug($data['treatment_name'], $treatment->id);
        }

        $imagePath = $imageService->replace($request->file('image'), $treatment->image);

        $treatment->update([
            'treatment_name' => $data['treatment_name'],
            'slug' => $slug,
            'category' => $data['category'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'image' => $imagePath,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.treatments.index')
            ->with('success', 'Treatment berhasil diperbarui.');
    }

    public function destroy(Treatment $treatment, TreatmentImageService $imageService)
    {
        if ($treatment->bookings()->exists()) {
            $treatment->update(['is_active' => false]);

            return redirect()->route('admin.treatments.index')
                ->with('success', 'Treatment memiliki data booking, sehingga hanya dinonaktifkan.');
        }

        $imageService->delete($treatment->image);
        $treatment->delete();

        return redirect()->route('admin.treatments.index')
            ->with('success', 'Treatment berhasil dihapus.');
    }

    public function toggleStatus(Treatment $treatment)
    {
        $treatment->update([
            'is_active' => !$treatment->is_active,
        ]);

        return redirect()->route('admin.treatments.index')
            ->with('success', 'Status treatment berhasil diperbarui.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Treatment::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}