@extends('layouts.app')

@section('title', 'Promotions - Lumiere Beauty Clinic')

@section('content')
    <main>
        <section class="page-hero">
            <div class="container page-hero-content">
                <span class="eyebrow eyebrow-dark">Special Offers</span>
                <h1>Promotions</h1>
                <p>
                    Pilih promo yang sesuai, lalu gunakan kodenya ketika melakukan booking treatment.
                </p>
            </div>
        </section>

        <section class="promotions section-block">
            <div class="container">
                <div class="promo-grid">
                    @forelse($promotions as $promotion)
                        @php
                            $userMinimumPoints = (int) ($user?->membershipLevel?->min_points ?? 0);
                            $requiredMinimumPoints = (int) ($promotion->minimumMembershipLevel?->min_points ?? 0);

                            $membershipEligible = !$promotion->membership_only
                                || ($user && $userMinimumPoints >= $requiredMinimumPoints);

                            $canUse = $user
                                && $membershipEligible
                                && filled($promotion->promo_code);
                        @endphp

                        <article class="promotion-card reveal card shadow-sm border-0 h-100 {{ $promotion->membership_only ? 'accent-card' : '' }}">
                            <div class="card-body p-4 d-flex flex-column">

                                <span class="promo-tag d-inline-block mb-3">
                                    {{ $promotion->membership_only ? 'Member Promotion' : 'Public Promotion' }}
                                </span>

                                <h3 class="card-title">
                                    {{ $promotion->title }}
                                </h3>

                                <p class="card-text">
                                    {{ $promotion->description }}
                                </p>

                                <p class="promotion-discount fw-bold mb-3">
                                    {{ number_format($promotion->discount_percent, 0) }}% OFF
                                </p>

                                <p class="mb-2">
                                    Code:
                                    <strong>
                                        {{ $promotion->promo_code ?? '-' }}
                                    </strong>
                                </p>

                                @if($promotion->membership_only)
                                    <p class="mb-2">
                                        Minimum:
                                        <strong>
                                            {{ $promotion->minimumMembershipLevel->level_name ?? 'Member' }}
                                        </strong>
                                    </p>
                                @endif

                                <p class="mb-2">
                                    <strong>Berlaku untuk:</strong><br>

                                    @if($promotion->treatments->isEmpty())
                                        Semua treatment
                                    @else
                                        {{ $promotion->treatments->pluck('treatment_name')->join(', ') }}
                                    @endif
                                </p>

                                @if(!$user)
                                    <span class="status-pill status-waiting_verification d-inline-block mb-3">
                                        Login untuk menggunakan promo
                                    </span>

                                @elseif(!$membershipEligible)
                                    <span class="status-pill status-waiting_verification d-inline-block mb-3">
                                        Level membership belum mencukupi
                                    </span>

                                @elseif(blank($promotion->promo_code))
                                    <span class="status-pill status-waiting_verification d-inline-block mb-3">
                                        Kode promo belum tersedia
                                    </span>

                                @else
                                    <span class="status-pill status-paid d-inline-block mb-3">
                                        Memenuhi persyaratan membership
                                    </span>
                                @endif

                                <p class="promo-period small text-muted mb-3">
                                    {{ $promotion->start_date?->format('d M Y') ?? 'Tanpa batas awal' }}
                                    sampai
                                    {{ $promotion->end_date?->format('d M Y') ?? 'Tanpa batas akhir' }}
                                </p>

                                <div class="mt-auto">
                                    @if($canUse)
                                        <a
                                            href="{{ route('treatments.index', ['promo' => $promotion->promo_code]) }}"
                                            class="btn btn-primary"
                                        >
                                            Gunakan Promo
                                        </a>

                                    @elseif(!$user)
                                        <a
                                            href="{{ route('login') }}"
                                            class="btn btn-outline btn-outline-secondary"
                                        >
                                            Login
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </article>

                    @empty
                        <article class="promotion-card reveal card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <span class="promo-tag d-inline-block mb-3">
                                    Promotion
                                </span>

                                <h3 class="card-title">
                                    No active promotions yet
                                </h3>

                                <p class="card-text mb-0">
                                    Promotions will appear after they are added by admin.
                                </p>
                            </div>
                        </article>
                    @endforelse
                </div>

                <div class="pagination-wrap mt-4">
                    {{ $promotions->links() }}
                </div>
            </div>
        </section>
    </main>
@endsection