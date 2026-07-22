@extends('layouts.app')

@section('title', 'Profile - Lumiere Beauty Clinic')

@section('content')
    <main>
        {{-- Bagian hero / header halaman profile --}}
        <section class="page-hero">
            <div class="container page-hero-content">
                <span class="eyebrow eyebrow-dark">My Account</span>
                <h1>Profile Membership</h1>
                <p>Informasi akun, level membership, dan statistik booking Anda.</p>
            </div>
        </section>

        <section class="section-block">
            <div class="container profile-layout">
                {{-- Card utama profile user --}}
                <article class="profile-card reveal card shadow-sm border-0 text-center">
                    <div class="card-body p-4 p-md-5">
                        <div class="profile-avatar mx-auto mb-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <h2 class="mb-2">{{ $user->name }}</h2>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        <span class="member-badge {{ strtolower($user->membershipLevel->level_name ?? 'bronze') }} d-inline-block">
                            {{ $user->membershipLevel->level_name ?? 'Bronze' }}
                        </span>
                    </div>
                </article>

                <div class="profile-detail-grid">
                    {{-- Card data akun user --}}
                    <article class="info-card reveal card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <span class="info-icon d-inline-block mb-3">👤</span>
                            <h3 class="card-title mb-3">Data Akun</h3>

                            <div class="profile-info-list">
                                <p class="mb-2">
                                    <strong>Nama:</strong> {{ $user->name }}
                                </p>
                                <p class="mb-2">
                                    <strong>Email:</strong> {{ $user->email }}
                                </p>
                                <p class="mb-2">
                                    <strong>Telepon:</strong> {{ $user->phone ?? '-' }}
                                </p>
                                <p class="mb-0">
                                    <strong>Status:</strong>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </article>

                    {{-- Card membership user --}}
                    <article class="info-card reveal card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <span class="info-icon d-inline-block mb-3">✨</span>
                            <h3 class="card-title mb-3">Membership</h3>

                            <div class="profile-info-list">
                                <p class="mb-2">
                                    <strong>Level:</strong>
                                    <span class="member-badge {{ strtolower($user->membershipLevel->level_name ?? 'bronze') }} d-inline-block">
                                        {{ $user->membershipLevel->level_name ?? 'Bronze' }}
                                    </span>
                                </p>
                                <p class="mb-2">
                                    <strong>Total Poin:</strong>
                                    <span class="fw-bold">{{ $user->total_points }}</span>
                                </p>
                                <p class="mb-0">
                                    <strong>Benefit:</strong>
                                    {{ $user->membershipLevel->benefits ?? 'Benefit membership dasar.' }}
                                </p>
                            </div>
                        </div>
                    </article>

                    {{-- Card statistik booking user --}}
                    <article class="info-card reveal card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <span class="info-icon d-inline-block mb-3">📅</span>
                            <h3 class="card-title mb-3">Statistik Booking</h3>

                            <div class="profile-info-list">
                                <p class="mb-2">
                                    <strong>Total Booking:</strong>
                                    <span class="fw-bold">{{ $totalBookings }}</span>
                                </p>
                                <p class="mb-2">
                                    <strong>Booking Paid:</strong>
                                    <span class="fw-bold text-success">{{ $paidBookings }}</span>
                                </p>
                                <p class="mb-0">
                                    <strong>Treatment Selesai:</strong>
                                    <span class="fw-bold">{{ $completedTreatments }}</span>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </main>
@endsection