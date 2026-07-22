@extends('layouts.app')

{{-- Mengatur judul halaman My Bookings yang tampil di tab browser --}}
@section('title', 'My Bookings - Lumiere Beauty Clinic')

@section('content')
    <main>
        {{-- Bagian hero/header halaman My Bookings --}}
        <section class="page-hero">
            <div class="container page-hero-content">
                <span class="eyebrow eyebrow-dark">Reservation History</span>
                <h1>My Bookings</h1>
                <p>Lihat status booking, pembayaran, treatment, dan receipt Anda.</p>
            </div>
        </section>

        {{-- Bagian utama halaman My Bookings --}}
        <section class="section-block">
            <div class="container">
                {{-- Card tabel yang menampilkan riwayat booking customer --}}
                <div class="table-card card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="table-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                            <div>
                                <h2 class="mb-1">Riwayat Booking</h2>
                                <p class="mb-0">Booking terbaru akan muncul di bagian atas.</p>
                            </div>

                            {{-- Tombol untuk membuat booking baru dan menuju halaman Treatments --}}
                            <a href="{{ route('treatments.index') }}" class="btn btn-primary">
                                Booking Baru
                            </a>
                        </div>

                        {{-- Tabel responsive agar tampilan booking tetap rapi di berbagai ukuran layar --}}
                        <div class="responsive-table table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Treatment</th>
                                        <th>Jadwal</th>
                                        <th>Total</th>
                                        <th>Pembayaran</th>
                                        <th>Booking</th>
                                        <th>Treatment</th>
                                        <th>Receipt</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {{-- Jika user memiliki data booking, tampilkan satu per satu.
                                         Jika belum ada booking, tampilkan pesan kosong. --}}
                                    @forelse($bookings as $booking)
                                        <tr>
                                            <td>
                                                {{-- Menampilkan kode booking dan tanggal booking dibuat --}}
                                                <strong>{{ $booking->booking_code }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $booking->created_at?->format('d M Y H:i') }}
                                                </small>
                                            </td>

                                            <td>
                                                {{-- Menampilkan nama treatment dari relasi treatment --}}
                                                {{ $booking->treatment->treatment_name ?? '-' }}
                                            </td>

                                            <td>
                                                {{-- Menampilkan tanggal dan jam booking treatment --}}
                                                {{ $booking->booking_date?->format('d M Y') }}<br>
                                                <small class="text-muted">{{ $booking->booking_time }}</small>
                                            </td>

                                            <td>
                                                {{-- Menampilkan total harga booking dalam format rupiah --}}
                                                <strong>
                                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                                </strong>
                                            </td>

                                            <td>
                                                {{-- Menampilkan status pembayaran, misalnya pending, waiting_verification, atau paid --}}
                                                <span class="status-pill status-{{ $booking->payment_status }} d-inline-block">
                                                    {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}
                                                </span>
                                            </td>

                                            <td>
                                                {{-- Menampilkan status booking customer --}}
                                                <span class="status-pill d-inline-block">
                                                    {{ ucfirst(str_replace('_', ' ', $booking->booking_status)) }}
                                                </span>
                                            </td>

                                            <td>
                                                {{-- Menampilkan status treatment, misalnya scheduled atau completed --}}
                                                <span class="status-pill d-inline-block">
                                                    {{ ucfirst(str_replace('_', ' ', $booking->treatment_status)) }}
                                                </span>
                                            </td>

                                            <td>
                                                {{-- Tombol receipt hanya muncul jika pembayaran sudah paid --}}
                                                @if($booking->payment_status === 'paid')
                                                    <a href="{{ route('payment.receipt', $booking) }}" class="btn btn-outline btn-outline-secondary btn-sm">
                                                        Lihat
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Form review hanya ditampilkan jika treatment sudah completed --}}
                                        @if($booking->treatment_status === 'completed')
                                            @php
                                                // Mengambil review milik user yang sedang login untuk booking ini.
                                                // Jika review sudah ada, form akan digunakan untuk edit review.
                                                $myReview = $booking->reviews
                                                    ->where('user_id', auth()->id())
                                                    ->first();
                                            @endphp

                                            <tr>
                                                <td colspan="8">
                                                    {{-- Details digunakan agar form review bisa dibuka/tutup oleh user --}}
                                                    <details class="review-details">
                                                        <summary class="fw-semibold">
                                                            {{-- Jika review sudah ada, tampilkan teks Edit Review.
                                                                 Jika belum ada, tampilkan teks Review This Booking. --}}
                                                            {{ $myReview ? 'Edit Review - ' . ($booking->treatment->treatment_name ?? 'Booking') : 'Review This Booking - ' . ($booking->treatment->treatment_name ?? 'Booking') }}
                                                        </summary>

                                                        <div class="review-box mt-3 p-3 p-md-4 rounded-4">
                                                            {{-- Informasi booking yang sedang diberi review --}}
                                                            <div class="review-target mb-3">
                                                                <strong>Booking Code:</strong> {{ $booking->booking_code }} <br>
                                                                <strong>Treatment:</strong> {{ $booking->treatment->treatment_name ?? '-' }} <br>
                                                                <strong>Schedule:</strong>
                                                                {{ $booking->booking_date?->format('d M Y') }}
                                                                {{ $booking->booking_time }}
                                                            </div>

                                                            {{-- Form review dikirim ke route reviews.store untuk disimpan atau diperbarui --}}
                                                            <form action="{{ route('reviews.store') }}" method="POST" class="auth-form">
                                                                {{-- Token keamanan Laravel untuk melindungi form dari request palsu --}}
                                                                @csrf

                                                                {{-- Menandai bahwa review ini ditujukan untuk data booking --}}
                                                                <input type="hidden" name="reviewable_type" value="booking">

                                                                {{-- Menyimpan ID booking yang akan diberi review --}}
                                                                <input type="hidden" name="reviewable_id" value="{{ $booking->id }}">

                                                                {{-- Input rating review --}}
                                                                <div class="form-group mb-3">
                                                                    <label for="rating-{{ $booking->id }}" class="form-label">Rating</label>
                                                                    <select
                                                                        id="rating-{{ $booking->id }}"
                                                                        name="rating"
                                                                        class="form-select"
                                                                        required
                                                                    >
                                                                        <option value="">Choose rating</option>

                                                                        {{-- Menampilkan pilihan rating dari 1 sampai 5 --}}
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <option value="{{ $i }}" {{ $myReview && $myReview->rating == $i ? 'selected' : '' }}>
                                                                                {{ $i }} Star
                                                                            </option>
                                                                        @endfor
                                                                    </select>
                                                                </div>

                                                                {{-- Input komentar review --}}
                                                                <div class="form-group mb-3">
                                                                    <label for="comment-{{ $booking->id }}" class="form-label">Comment</label>
                                                                    <textarea
                                                                        id="comment-{{ $booking->id }}"
                                                                        name="comment"
                                                                        rows="3"
                                                                        class="form-control"
                                                                        placeholder="Write your experience"
                                                                    >{{ $myReview->comment ?? '' }}</textarea>
                                                                </div>

                                                                {{-- Tombol submit review.
                                                                     Jika review sudah ada, teksnya Update Review. Jika belum, Submit Review. --}}
                                                                <button type="submit" class="btn btn-primary">
                                                                    {{ $myReview ? 'Update Review' : 'Submit Review' }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </details>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        {{-- Pesan jika user belum memiliki data booking --}}
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                Anda belum memiliki booking.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Menampilkan pagination jika data booking lebih dari 5 --}}
                        <div class="pagination-wrap mt-4">
                            {{ $bookings->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection