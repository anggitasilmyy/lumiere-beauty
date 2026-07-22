@extends('layouts.admin')

@section('title', 'Dashboard Admin - Lumiere Beauty Clinic')

@section('admin_content')
    <section class="section-block">
        <div class="container admin-page-shell">
            <div class="admin-toolbar reveal d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4 mb-4">
                <div>
                    <span class="eyebrow eyebrow-dark">Admin Panel</span>
                    <h1>Dashboard Admin</h1>
                    <p class="mb-0">Ringkasan data treatment, customer, booking, pembayaran, dan revenue.</p>
                </div>

                <div class="admin-toolbar-actions d-flex flex-column flex-md-row gap-2">
                    <a href="{{ route('admin.treatments.create') }}" class="btn btn-primary">
                        Add Treatment
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline btn-outline-secondary">
                        Booking Verification
                    </a>
                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline btn-outline-secondary">
                        Manage Promotions
                    </a>
                </div>
            </div>

            <div class="admin-kpi-grid">
                <article class="admin-kpi-card reveal card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-2">{{ $totalTreatments }}</h3>
                        <p class="mb-0">Total Treatment</p>
                    </div>
                </article>

                <article class="admin-kpi-card reveal card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-2">{{ $totalActiveTreatments }}</h3>
                        <p class="mb-0">Treatment Aktif</p>
                    </div>
                </article>

                <article class="admin-kpi-card reveal card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-2">{{ $totalCustomers }}</h3>
                        <p class="mb-0">Total Customer</p>
                    </div>
                </article>

                <article class="admin-kpi-card reveal card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-2">{{ $totalBookings }}</h3>
                        <p class="mb-0">Total Booking</p>
                    </div>
                </article>

                <article class="admin-kpi-card reveal card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-2">{{ $totalPendingBookings }}</h3>
                        <p class="mb-0">Booking Pending</p>
                    </div>
                </article>

                <article class="admin-kpi-card reveal card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-2">{{ $totalWaitingPayments }}</h3>
                        <p class="mb-0">Waiting Verification</p>
                    </div>
                </article>

                <article class="admin-kpi-card reveal card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-2">{{ $totalCompletedTreatments }}</h3>
                        <p class="mb-0">Treatment Selesai</p>
                    </div>
                </article>

                <article class="admin-kpi-card reveal card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-2 fs-5">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </h3>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                </article>
            </div>

            <div class="table-card reveal card shadow-sm border-0 mt-4">
                <div class="card-body p-4 p-md-5">
                    <div class="table-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                        <div>
                            <h2 class="mb-1">Booking Terbaru</h2>
                            <p class="mb-0">5 data booking terbaru customer.</p>
                        </div>

                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary btn-sm">
                            Lihat Semua Booking
                        </a>
                    </div>

                    <div class="responsive-table table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th>Treatment</th>
                                    <th>Jadwal</th>
                                    <th>Payment</th>
                                    <th>Status Booking</th>
                                    <th>Status Treatment</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($latestBookings as $booking)
                                    <tr>
                                        <td>
                                            <strong>{{ $booking->booking_code }}</strong><br>
                                            <small class="text-muted">
                                                {{ $booking->created_at?->format('d M Y H:i') }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $booking->user->name ?? '-' }}<br>
                                            <small class="text-muted">
                                                {{ $booking->user->email ?? '-' }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $booking->treatment->treatment_name ?? '-' }}<br>
                                            <small class="text-muted">
                                                {{ $booking->treatment->category ?? '-' }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $booking->booking_date?->format('d M Y') }}<br>
                                            <small class="text-muted">
                                                {{ $booking->booking_time }}
                                            </small>
                                        </td>

                                        <td>
                                            <span class="status-pill status-{{ $booking->payment_status }} d-inline-block text-capitalize">
                                                {{ str_replace('_', ' ', $booking->payment_status) }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="status-pill d-inline-block text-capitalize">
                                                {{ str_replace('_', ' ', $booking->booking_status) }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="status-pill d-inline-block text-capitalize">
                                                {{ str_replace('_', ' ', $booking->treatment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            Belum ada data booking.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection