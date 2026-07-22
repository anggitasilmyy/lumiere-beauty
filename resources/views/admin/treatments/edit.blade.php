@extends('layouts.admin')

@section('title', 'Edit Treatment - Lumiere Beauty Clinic')

@section('admin_content')
    <section class="section-block">
        <div class="container admin-page-shell">
            <div class="admin-toolbar reveal d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4 mb-4">
                <div>
                    <span class="eyebrow eyebrow-dark">Edit Treatment</span>
                    <h1>{{ $treatment->treatment_name }}</h1>
                    <p class="mb-0">
                        Perbarui informasi treatment, harga, durasi, status, dan gambar.
                    </p>
                </div>

                <div class="admin-toolbar-actions">
                    <a href="{{ route('admin.treatments.index') }}" class="btn btn-outline btn-outline-secondary">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="table-card reveal card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.treatments.update', $treatment) }}" method="POST" enctype="multipart/form-data" class="admin-form-grid">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="treatment_name" class="form-label">Nama Treatment</label>
                            <input
                                type="text"
                                id="treatment_name"
                                name="treatment_name"
                                value="{{ old('treatment_name', $treatment->treatment_name) }}"
                                class="form-control @error('treatment_name') is-invalid @enderror"
                                placeholder="Contoh: Brightening Skin Therapy"
                                required
                            >

                            @error('treatment_name')
                                <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <input
                                type="text"
                                id="category"
                                name="category"
                                value="{{ old('category', $treatment->category) }}"
                                class="form-control @error('category') is-invalid @enderror"
                                placeholder="Contoh: Facial, Skin Care, Body Treatment"
                            >

                            @error('category')
                                <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input
                                type="number"
                                id="price"
                                name="price"
                                value="{{ old('price', $treatment->price) }}"
                                min="0"
                                class="form-control @error('price') is-invalid @enderror"
                                placeholder="Contoh: 250000"
                                required
                            >

                            @error('price')
                                <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="duration_minutes" class="form-label">Durasi Menit</label>
                            <input
                                type="number"
                                id="duration_minutes"
                                name="duration_minutes"
                                value="{{ old('duration_minutes', $treatment->duration_minutes) }}"
                                min="1"
                                class="form-control @error('duration_minutes') is-invalid @enderror"
                                placeholder="Contoh: 60"
                            >

                            @error('duration_minutes')
                                <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group admin-form-full mb-3">
                            <label for="short_description" class="form-label">Deskripsi Singkat</label>
                            <input
                                type="text"
                                id="short_description"
                                name="short_description"
                                value="{{ old('short_description', $treatment->short_description) }}"
                                class="form-control @error('short_description') is-invalid @enderror"
                                placeholder="Deskripsi singkat untuk card treatment"
                            >

                            @error('short_description')
                                <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group admin-form-full mb-3">
                            <label for="description" class="form-label">Deskripsi Lengkap</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="6"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Jelaskan detail treatment"
                            >{{ old('description', $treatment->description) }}</textarea>

                            @error('description')
                                <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group admin-form-full mb-3">
                            <label class="form-label">Gambar Saat Ini</label>

                            @if($treatment->image)
                                <div class="admin-current-image mb-3">
                                    <img
                                        src="{{ str_starts_with($treatment->image, 'assets/') ? asset($treatment->image) : asset('storage/' . $treatment->image) }}"
                                        alt="{{ $treatment->treatment_name }}"
                                        class="img-fluid rounded-4 shadow-sm"
                                    >
                                </div>
                            @else
                                <div class="alert alert-light border mb-3">
                                    Belum ada gambar.
                                </div>
                            @endif

                            <label for="image" class="form-label">Ganti Gambar Treatment</label>
                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept="image/*"
                                class="form-control @error('image') is-invalid @enderror"
                            >
                            <small class="form-text text-muted">
                                Kosongkan jika tidak ingin mengganti gambar. Format JPG, JPEG, PNG, WEBP.
                            </small>

                            @error('image')
                                <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="admin-checkbox-group admin-form-full d-flex flex-column flex-md-row gap-3 mb-4">
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    id="is_featured"
                                    name="is_featured"
                                    value="1"
                                    class="form-check-input"
                                    {{ old('is_featured', $treatment->is_featured) ? 'checked' : '' }}
                                >
                                <label for="is_featured" class="form-check-label">
                                    Jadikan treatment unggulan
                                </label>
                            </div>

                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    class="form-check-input"
                                    {{ old('is_active', $treatment->is_active) ? 'checked' : '' }}
                                >
                                <label for="is_active" class="form-check-label">
                                    Aktif
                                </label>
                            </div>
                        </div>

                        <div class="admin-form-actions admin-form-full d-flex flex-column flex-md-row gap-2">
                            <button type="submit" class="btn btn-primary">
                                Update Treatment
                            </button>

                            <a href="{{ route('admin.treatments.index') }}" class="btn btn-outline btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection