@extends('layouts.app')

@section('title', 'My Points - Lumiere Beauty Clinic')

@section('content')
    <main>
        {{-- Bagian hero/header halaman My Points --}}
        <section class="page-hero">
            <div class="container page-hero-content">
                <span class="eyebrow eyebrow-dark">Membership Points</span>
                <h1>My Points</h1>
                <p>Poin membership diperoleh setelah pembayaran booking diverifikasi admin.</p>
            </div>
        </section>

        {{-- Bagian utama halaman My Points --}}
        <section class="section-block">
            <div class="container">
                {{-- Ringkasan membership dan poin user --}}
                <div class="membership-summary">
                    <article class="membership-card reveal card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <span class="member-badge {{ strtolower($user->membershipLevel->level_name ?? 'bronze') }} d-inline-block mb-3">
                                {{ $user->membershipLevel->level_name ?? 'Bronze' }}
                            </span>

                            <h3 class="card-title mb-3">Current Level</h3>

                            {{-- Menampilkan benefit sesuai level membership user --}}
                            <p class="card-text">
                                {{ $user->membershipLevel->benefits ?? 'Benefit membership dasar.' }}
                            </p>

                            {{-- Menampilkan total poin aktif yang dimiliki user --}}
                            <strong class="d-block mt-3 fs-4">
                                {{ $user->total_points }} Points
                            </strong>
                        </div>
                    </article>

                    {{-- Card ajakan agar user melakukan booking untuk menambah poin --}}
                    <article class="membership-card reveal card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <span class="member-badge silver d-inline-block mb-3">
                                Next Benefit
                            </span>

                            <h3 class="card-title mb-3">Collect More Points</h3>

                            <p class="card-text">
                                Semakin banyak transaksi paid, semakin tinggi level membership yang dapat dicapai.
                            </p>

                            {{-- Tombol menuju halaman treatment untuk melakukan booking --}}
                            <a href="{{ route('treatments.index') }}" class="btn btn-primary mt-2">
                                Book Treatment
                            </a>
                        </div>
                    </article>
                </div>

                {{-- Card tabel riwayat transaksi poin --}}
                <div class="table-card reveal card shadow-sm border-0 mt-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="table-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                            <div>
                                <h2 class="mb-1">Riwayat Poin</h2>
                                <p class="mb-0">Catatan earn, redeem, adjustment, atau expired point.</p>
                            </div>
                        </div>

                        {{-- Tabel responsive agar riwayat poin tetap rapi di berbagai ukuran layar --}}
                        <div class="responsive-table table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Poin</th>
                                        <th>Deskripsi</th>
                                        <th>Kedaluwarsa</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {{-- Jika ada transaksi poin, tampilkan satu per satu.
                                         Jika tidak ada, tampilkan pesan bahwa riwayat poin masih kosong. --}}
                                    @forelse($pointTransactions as $transaction)
                                        <tr>
                                            {{-- Menampilkan tanggal transaksi poin dibuat --}}
                                            <td>
                                                {{ $transaction->created_at?->format('d M Y') }}
                                            </td>

                                            {{-- Menampilkan tipe transaksi, misalnya earn, redeem, adjustment, atau expired --}}
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    {{ ucfirst($transaction->transaction_type) }}
                                                </span>
                                            </td>

                                            {{-- Menampilkan jumlah poin pada transaksi --}}
                                            <td>
                                                <strong class="{{ $transaction->points >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $transaction->points >= 0 ? '+' : '' }}{{ $transaction->points }}
                                                </strong>
                                            </td>

                                            {{-- Menampilkan deskripsi transaksi poin --}}
                                            <td>
                                                {{ $transaction->description ?? '-' }}
                                            </td>

                                            {{-- Menampilkan tanggal kedaluwarsa poin jika ada --}}
                                            <td>
                                                {{ $transaction->expired_at?->format('d M Y') ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Pesan jika user belum memiliki riwayat poin --}}
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                Belum ada riwayat poin.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Menampilkan pagination jika riwayat poin lebih dari 10 data --}}
                        <div class="pagination-wrap mt-4">
                            {{ $pointTransactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection