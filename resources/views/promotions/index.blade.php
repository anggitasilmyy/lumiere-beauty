@extends('layouts.app')

@section('title', 'Promotions - Lumiere Beauty Clinic')

@section('content')
    <main>
        <section class="page-hero">
            <div class="container page-hero-content">
                <span class="eyebrow eyebrow-dark">Special Offers</span>
                <h1>Promotions</h1>
                <p>
                    Explore active promotions for public customers and Lumiere Beauty members.
                </p>
            </div>
        </section>

        <section class="promotions section-block">
            <div class="container">
                <div class="promo-grid">
                    @forelse($promotions as $promotion)
                        <article class="promotion-card reveal card shadow-sm border-0 h-100 {{ $promotion->membership_only ? 'accent-card' : '' }}">
                            <div class="card-body p-4">
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

                                @if($promotion->promo_code)
                                    <p class="mb-2">
                                        Code:
                                        <strong>{{ $promotion->promo_code }}</strong>
                                    </p>
                                @endif

                                @if($promotion->membership_only)
                                    <p class="mb-2">
                                        Minimum:
                                        <strong>{{ $promotion->minimumMembershipLevel->level_name ?? 'Member' }}</strong>
                                    </p>

                                    @auth
                                        @if(auth()->user()->membership_level_id >= $promotion->minimum_membership_level_id)
                                            <span class="status-pill status-paid d-inline-block mb-3">
                                                Dapat digunakan
                                            </span>
                                        @else
                                            <span class="status-pill status-waiting_verification d-inline-block mb-3">
                                                Level belum mencukupi
                                            </span>
                                        @endif
                                    @else
                                        <span class="status-pill status-waiting_verification d-inline-block mb-3">
                                            Login untuk cek benefit
                                        </span>
                                    @endauth
                                @else
                                    <span class="status-pill status-paid d-inline-block mb-3">
                                        Untuk semua customer
                                    </span>
                                @endif

                                <p class="promo-period small text-muted mb-0">
                                    {{ $promotion->start_date?->format('d M Y') ?? '-' }}
                                    sampai
                                    {{ $promotion->end_date?->format('d M Y') ?? '-' }}
                                </p>
                            </div>
                        </article>
                    @empty
                        <article class="promotion-card reveal card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <span class="promo-tag d-inline-block mb-3">Promotion</span>
                                <h3 class="card-title">No active promotions yet</h3>
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