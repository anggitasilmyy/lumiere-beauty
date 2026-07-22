@extends('layouts.app')

{{-- Mengatur judul halaman register yang tampil di tab browser --}}
@section('title', 'Register - Lumiere Beauty Clinic')

@section('content')
    <main>
        {{-- Section utama untuk halaman register --}}
        <section class="auth-section py-5">
            <div class="container auth-layout">
                {{-- Bagian kiri halaman register yang berisi penjelasan manfaat menjadi member --}}
                <div class="auth-copy reveal mb-4 mb-lg-0">
                    <span class="eyebrow eyebrow-dark">Join Membership</span>
                    <h1>Create Your Account</h1>
                    <p>
                        Daftar sebagai customer Lumiere Beauty untuk mendapatkan akses booking online,
                        membership points, and member-only promotions.
                    </p>

                    {{-- Daftar benefit yang didapat user setelah membuat akun --}}
                    <div class="auth-benefit">
                        <h3>Why Join?</h3>
                        <ul class="mb-0">
                            <li>Mulai dari level Bronze</li>
                            <li>Dapatkan poin setelah pembayaran diverifikasi</li>
                            <li>Naik level ke Silver, Gold, dan Platinum</li>
                            <li>Access exclusive membership promotions</li>
                        </ul>
                    </div>
                </div>

                {{-- Card form register customer --}}
                <div class="auth-card reveal card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="text-center mb-2">Register</h2>
                        <p class="text-center mb-4">Buat akun customer Lumiere Beauty.</p>

                        {{-- Form register dikirim ke route register.store untuk diproses oleh AuthController@register --}}
                        <form action="{{ route('register.store') }}" method="POST" class="auth-form">
                            {{-- Token keamanan Laravel untuk melindungi form dari request palsu --}}
                            @csrf

                            {{-- Input nama lengkap customer --}}
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                >

                                {{-- Menampilkan pesan error jika validasi nama gagal --}}
                                @error('name')
                                    <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Input email customer yang digunakan untuk login --}}
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

                            {{-- Input nomor telepon customer --}}
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Contoh: 081234567890"
                                >

                                {{-- Menampilkan pesan error jika validasi nomor telepon gagal --}}
                                @error('phone')
                                    <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Input password akun customer --}}
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimal 6 karakter"
                                    required
                                >

                                {{-- Menampilkan pesan error jika validasi password gagal --}}
                                @error('password')
                                    <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Input konfirmasi password.
                                 Name password_confirmation wajib sama dengan rule confirmed di AuthController --}}
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Ulangi password"
                                    required
                                >

                                {{-- Menampilkan pesan error jika validasi konfirmasi password gagal --}}
                                @error('password_confirmation')
                                    <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Tombol untuk mengirim data register ke AuthController --}}
                            <button type="submit" class="btn btn-primary btn-block w-100 mt-2">
                                Register
                            </button>
                        </form>

                        {{-- Link menuju halaman login jika user sudah memiliki akun --}}
                        <p class="auth-switch text-center mt-3 mb-0">
                            Sudah punya akun?
                            <a href="{{ route('login') }}">Login di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection