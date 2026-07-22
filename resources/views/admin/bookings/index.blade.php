@extends('layouts.admin')

@section('title', 'Booking Management - Lumiere Beauty Clinic')

@section('admin_content')
    <section class="section-block">
        <div class="container admin-page-shell">
            <div class="admin-toolbar reveal d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4 mb-4">
                <div>
                    <span class="eyebrow eyebrow-dark">Booking Management</span>
                    <h1>Booking Management</h1>
                    <p class="mb-0">Verify payment, booking status, and customer treatment status.</p>
                </div>
            </div>

            <div class="table-card reveal card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.bookings.index') }}" method="GET" class="admin-filter-grid">
                        <div class="form-group">
                            <label for="search" class="form-label">Cari Data</label>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Kode booking, customer, treatment"
                                class="form-control"
                            >
                        </div>

                        <div class="form-group">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-select">
                                <option value="">All Payments</option>
                                @foreach(['unpaid', 'waiting_verification', 'paid', 'failed', 'refunded'] as $status)
                                    <option value="{{ $status }}" {{ request('payment_status') === $status ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', $status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="booking_status" class="form-label">Booking Status</label>
                            <select name="booking_status" id="booking_status" class="form-select">
                                <option value="">Semua Booking</option>
                                @foreach(['pending', 'confirmed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ request('booking_status') === $status ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', $status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="treatment_status" class="form-label">Treatment Status</label>
                            <select name="treatment_status" id="treatment_status" class="form-select">
                                <option value="">Semua Treatment</option>
                                @foreach(['not_started', 'scheduled', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ request('treatment_status') === $status ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', $status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="admin-filter-actions d-flex flex-column flex-md-row gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="booking-admin-list">
                @forelse($bookings as $booking)
                    <article class="booking-admin-card reveal card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <div class="booking-admin-grid">
                                <div class="booking-admin-info">
                                    <div class="booking-admin-header">
                                        <div>
                                            <span class="promo-tag">Booking</span>
                                            <h2>{{ $booking->booking_code }}</h2>
                                            <p>{{ $booking->created_at?->format('d M Y H:i') }}</p>
                                        </div>

                                        <div class="booking-admin-badges">
                                            <span class="status-pill status-{{ $booking->payment_status }}">
                                                {{ str_replace('_', ' ', $booking->payment_status) }}
                                            </span>
                                            <span class="status-pill">
                                                {{ str_replace('_', ' ', $booking->booking_status) }}
                                            </span>
                                            <span class="status-pill">
                                                {{ str_replace('_', ' ', $booking->treatment_status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="booking-admin-meta">
                                        <div>
                                            <h4>Customer</h4>
                                            <p>
                                                {{ $booking->user->name ?? '-' }}<br>
                                                <small>{{ $booking->user->email ?? '-' }}</small>
                                            </p>
                                        </div>

                                        <div>
                                            <h4>Treatment</h4>
                                            <p>
                                                {{ $booking->treatment->treatment_name ?? '-' }}<br>
                                                <small>{{ $booking->treatment->category ?? '-' }}</small>
                                            </p>
                                        </div>

                                        <div>
                                            <h4>Jadwal</h4>
                                            <p>
                                                {{ $booking->booking_date?->format('d M Y') }}<br>
                                                <small>{{ $booking->booking_time }}</small>
                                            </p>
                                        </div>

                                        <div>
                                            <h4>Payment</h4>
                                            <p>
                                                Metode: {{ $booking->payment ? str_replace('_', ' ', $booking->payment->payment_method) : '-' }}<br>
                                                <small>Payer: {{ $booking->payment->payer_name ?? '-' }}</small>
                                            </p>
                                        </div>

                                        <div>
                                            <h4>Total</h4>
                                            <p><strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></p>
                                        </div>

                                        <div>
                                            <h4>Referensi</h4>
                                            <p>{{ $booking->payment->payment_reference ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="booking-status-box card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h3>Update Status</h3>

                                        <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="booking-status-form">
                                            @csrf
                                            @method('PATCH')

                                            <div class="form-group">
                                                <label for="payment_status_{{ $booking->id }}" class="form-label">Payment</label>
                                                <select name="payment_status" id="payment_status_{{ $booking->id }}" class="form-select">
                                                    @foreach(['unpaid', 'waiting_verification', 'paid', 'failed', 'refunded'] as $status)
                                                        <option value="{{ $status }}" {{ $booking->payment_status === $status ? 'selected' : '' }}>
                                                            {{ str_replace('_', ' ', $status) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="booking_status_{{ $booking->id }}" class="form-label">Booking</label>
                                                <select name="booking_status" id="booking_status_{{ $booking->id }}" class="form-select">
                                                    @foreach(['pending', 'confirmed', 'cancelled'] as $status)
                                                        <option value="{{ $status }}" {{ $booking->booking_status === $status ? 'selected' : '' }}>
                                                            {{ str_replace('_', ' ', $status) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="treatment_status_{{ $booking->id }}" class="form-label">Treatment</label>
                                                <select name="treatment_status" id="treatment_status_{{ $booking->id }}" class="form-select">
                                                    @foreach(['not_started', 'scheduled', 'completed', 'cancelled'] as $status)
                                                        <option value="{{ $status }}" {{ $booking->treatment_status === $status ? 'selected' : '' }}>
                                                            {{ str_replace('_', ' ', $status) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="booking-status-actions d-flex flex-column gap-2">
                                                <button type="submit" class="btn btn-primary btn-block">Update Status</button>

                                                @if($booking->payment_status === 'paid')
                                                    <a href="{{ route('payment.receipt', $booking) }}" target="_blank" class="btn btn-outline btn-block">
                                                        Lihat Receipt
                                                    </a>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="table-card reveal card shadow-sm border-0">
                        <div class="card-body p-4">
                            <p class="mb-0">Belum ada data booking.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="pagination-wrap mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </section>
@endsection