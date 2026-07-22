@extends('layouts.app')

{{-- Mengatur judul halaman login yang tampil di tab browser --}}
@section('title', 'Login - Lumiere Beauty Clinic')

@section('content')
    <main>
        {{-- Section utama untuk halaman login --}}
        <section class="auth-section py-5">
            <div class="container auth-layout">
                {{-- Bagian kiri halaman login yang berisi informasi benefit member --}}
                <div class="auth-copy reveal mb-4 mb-lg-0">
                    <span class="eyebrow eyebrow-dark">Member Access</span>
                    <h1>Welcome Back</h1>
                    <p>
                        Login untuk melakukan booking treatment, melihat riwayat pembayaran,
                        mengecek poin, dan mengakses benefit membership Lumiere Beauty.
                    </p>

                    {{-- Daftar benefit yang bisa diakses setelah user login --}}
                    <div class="auth-benefit">
                        <h3>Member Benefit</h3>
                        <ul class="mb-0">
                            <li>Booking treatment online</li>
                            <li>Riwayat pembayaran dan receipt</li>
                            <li>Point membership dari transaksi paid</li>
                            <li>Promo khusus sesuai level membership</li>
                        </ul>
                    </div>
                </div>

                {{-- Card form login --}}
                <div class="auth-card reveal card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="text-center mb-2">Login</h2>
                        <p class="text-center mb-4">Masuk ke akun Lumiere Beauty.</p>

                        {{-- Form login dikirim ke route login.store untuk diproses oleh AuthController@login --}}
                        <form action="{{ route('login.store') }}" method="POST" class="auth-form">
                            {{-- Token keamanan Laravel untuk melindungi form dari request palsu --}}
                            @csrf

                            {{-- Input email user yang sudah terdaftar --}}
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Masukkan email"
                                    required
                                >

                                {{-- Menampilkan pesan error jika validasi email gagal --}}
                                @error('email')
                                    <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Input password user --}}
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password"
                                    required
                                >

                                {{-- Menampilkan pesan error jika validasi password gagal --}}
                                @error('password')
                                    <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Checkbox remember me untuk menyimpan session login lebih lama --}}
                            <div class="form-check d-flex align-items-center gap-2 mb-3 remember-row">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                    value="1"
                                    class="form-check-input remember-checkbox"
                                >
                                <label for="remember" class="form-check-label remember-label">
                                    Ingat saya
                                </label>
                            </div>

                            {{-- Tombol untuk mengirim data login ke AuthController --}}
                            <button type="submit" class="btn btn-primary btn-block w-100">
                                Login
                            </button>
                        </form>

                        {{-- Link menuju halaman register jika user belum memiliki akun --}}
                        <p class="auth-switch text-center mt-3 mb-0">
                            Belum punya akun?
                            <a href="{{ route('register') }}">Register sekarang</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection