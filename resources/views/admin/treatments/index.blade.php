@extends('layouts.admin')

@section('title', 'Manage Treatments - Lumiere Beauty Clinic')

@section('admin_content')
    <section class="section-block">
        <div class="container admin-page-shell">
            <div class="admin-toolbar reveal d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4 mb-4">
                <div>
                    <span class="eyebrow eyebrow-dark">Treatment Management</span>
                    <h1>Kelola Treatment</h1>
                    <p class="mb-0">
                        Add, edit, activate, deactivate, and delete Lumiere Beauty treatment services.
                    </p>
                </div>

                <div class="admin-toolbar-actions d-flex flex-column flex-md-row gap-2">
                    <a href="{{ route('admin.treatments.create') }}" class="btn btn-primary">
                        Add Treatment
                    </a>

                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-outline-secondary">
                        Dashboard
                    </a>
                </div>
            </div>

            <div class="table-card reveal card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.treatments.index') }}" method="GET" class="admin-filter-grid">
                        <div class="form-group mb-3">
                            <label for="search" class="form-label">Cari Treatment</label>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Nama, kategori, atau deskripsi"
                            >
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>
                        </div>

                        <div class="admin-filter-actions d-flex flex-column flex-md-row gap-2 align-items-md-end">
                            <button type="submit" class="btn btn-primary">
                                Filter
                            </button>
                            <a href="{{ route('admin.treatments.index') }}" class="btn btn-outline btn-outline-secondary">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="admin-treatment-grid">
                @forelse($treatments as $treatment)
                    <article class="admin-treatment-card reveal card shadow-sm border-0 overflow-hidden h-100">
                        <div class="admin-treatment-image">
                            @if($treatment->image)
                                <img
                                    src="{{ str_starts_with($treatment->image, 'assets/') ? asset($treatment->image) : asset('storage/' . $treatment->image) }}"
                                    alt="{{ $treatment->treatment_name }}"
                                    class="img-fluid"
                                >
                            @else
                                <div class="admin-no-image d-flex align-items-center justify-content-center">
                                    No Image
                                </div>
                            @endif
                        </div>

                        <div class="admin-treatment-body card-body p-4">
                            <div class="booking-admin-header d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
                                <div>
                                    <span class="promo-tag d-inline-block mb-3">
                                        {{ $treatment->category ?? 'Treatment' }}
                                    </span>

                                    <h2 class="card-title mb-2">
                                        {{ $treatment->treatment_name }}
                                    </h2>

                                    <p class="card-text mb-0">
                                        {{ $treatment->short_description ?? 'Tidak ada deskripsi singkat.' }}
                                    </p>
                                </div>

                                <div class="booking-admin-badges d-flex flex-wrap gap-2">
                                    @if($treatment->is_active)
                                        <span class="status-pill status-paid d-inline-block">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="status-pill status-cancelled d-inline-block">
                                            Nonaktif
                                        </span>
                                    @endif

                                    @if($treatment->is_featured)
                                        <span class="status-pill status-waiting_verification d-inline-block">
                                            Featured
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="booking-admin-meta row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 border h-100">
                                        <h4 class="mb-2">Harga</h4>
                                        <p class="mb-0">
                                            <strong>Rp {{ number_format($treatment->price, 0, ',', '.') }}</strong>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 border h-100">
                                        <h4 class="mb-2">Durasi</h4>
                                        <p class="mb-0">
                                            {{ $treatment->duration_minutes ? $treatment->duration_minutes . ' menit' : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 border h-100">
                                        <h4 class="mb-2">Slug</h4>
                                        <p class="mb-0 text-break">
                                            {{ $treatment->slug }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-card-actions d-flex flex-column flex-md-row flex-wrap gap-2">
                                <a href="{{ route('admin.treatments.edit', $treatment) }}" class="btn btn-primary">
                                    Edit
                                </a>

                                <form action="{{ route('admin.treatments.toggle-status', $treatment) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="btn btn-outline btn-outline-secondary w-100">
                                        {{ $treatment->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form
                                    action="{{ route('admin.treatments.destroy', $treatment) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus treatment ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger-soft w-100">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="table-card reveal card shadow-sm border-0">
                        <div class="card-body p-4 text-center">
                            <p class="mb-0">Belum ada data treatment.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="pagination-wrap mt-4">
                {{ $treatments->links() }}
            </div>
        </div>
    </section>
@endsection