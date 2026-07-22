@extends('layouts.app')
@section('title', 'Lumiere Beauty Clinic')
@section('content')
    <main>
        <section class="hero" id="home">
            <div class="hero-slider" aria-label="Galeri klinik kecantikan">
                <div class="hero-slides" id="heroSlides"></div>
                <div class="hero-overlay"></div>

                <div class="container hero-content-wrapper">
                    <div class="hero-copy">
                        <span class="eyebrow" id="heroBadge">Beauty Clinic Premium</span>

                        <h1 id="heroTitle">Reveal Your Natural Radiance</h1>

                        <p id="heroDescription">
                            Experience luxury beauty treatments tailored to enhance your natural beauty in a calm, elegant, and professional environment.
                        </p>

                        <div class="hero-actions">
                            <a href="{{ route('treatments.index') }}" class="btn btn-primary">Explore Treatments</a>
                            <a href="#promotion" class="btn btn-secondary">View Promotions</a>
                        </div>

                        <div class="hero-meta">
                            <div>
                                <strong>Professional Team</strong>
                                <span>Dokter & terapis berpengalaman</span>
                            </div>

                            <div>
                                <strong>Personalized Care</strong>
                                <span>Perawatan disesuaikan kebutuhan kulit</span>
                            </div>
                        </div>
                    </div>

                    <aside class="hero-panel">
                        <span class="panel-label">Today's Highlight</span>

                        <h2>Glow Treatment</h2>

                        <p>
                            Dapatkan rekomendasi treatment sesuai kondisi kulitmu dengan proses reservasi yang cepat.
                        </p>

                        <ul class="hero-panel-list">
                            <li>Analisis kebutuhan kulit</li>
                            <li>Jadwal fleksibel sesuai pilihan</li>
                            <li>Special promotions for members</li>
                        </ul>

                        <a href="{{ route('treatments.index') }}" class="btn btn-primary btn-block">
                            Book Treatment
                        </a>
                    </aside>
                </div>

                <button class="slider-control prev" id="prevSlide" aria-label="Slide sebelumnya">❮</button>
                <button class="slider-control next" id="nextSlide" aria-label="Slide berikutnya">❯</button>
                <div class="slider-dots" id="sliderDots" aria-label="Navigasi slide"></div>
            </div>
        </section>

        <section class="quick-info">
            <div class="container quick-info-grid">
                <article class="info-card reveal">
                    <span class="info-icon">✨</span>
                    <h3>Premium Experience</h3>
                    <p>Interior modern, suasana nyaman, dan pelayanan yang hangat sejak pertama datang.</p>
                </article>

                <article class="info-card reveal">
                    <span class="info-icon">🧴</span>
                    <h3>Trusted Products</h3>
                    <p>Menggunakan produk dan metode perawatan yang dipilih secara profesional.</p>
                </article>

                <article class="info-card reveal">
                    <span class="info-icon">📅</span>
                    <h3>Easy Reservation</h3>
                    <p>Pilih treatment, tentukan jadwal, lakukan pembayaran, lalu nikmati pengalaman treatment.</p>
                </article>
            </div>
        </section>

        <section class="stats-section">
            <div class="container stats-grid">
                <div class="stat-card reveal">
                    <strong class="counter" data-target="1200">0</strong>
                    <span>Happy Clients</span>
                </div>

                <div class="stat-card reveal">
                    <strong class="counter" data-target="18">0</strong>
                    <span>Beauty Specialists</span>
                </div>

                <div class="stat-card reveal">
                    <strong class="counter" data-target="25">0</strong>
                    <span>Popular Treatments</span>
                </div>

                <div class="stat-card reveal">
                    <strong class="counter" data-target="8">0</strong>
                    <span>Years of Care</span>
                </div>
            </div>
        </section>

        <section class="treatments section-block" id="treatments">
            <div class="container">
                <div class="section-heading reveal">
                    <span class="eyebrow eyebrow-dark">Selected Treatments</span>
                    <h2>Perawatan unggulan untuk kulit yang sehat dan bercahaya</h2>
                    <p>Pilih treatment yang paling sesuai, lalu lanjutkan ke halaman Treatments untuk proses booking.</p>
                </div>

                <div class="treatment-grid">
                    @forelse($featuredTreatments as $treatment)
                        <article class="treatment-card reveal" data-treatment="{{ $treatment->treatment_name }}">
                            <div class="treatment-badge">
                                {{ $treatment->category ?? 'Treatment' }}
                            </div>

                            <h3>{{ $treatment->treatment_name }}</h3>

                            <p>{{ $treatment->short_description }}</p>

                            <ul>
                                <li>Durasi {{ $treatment->duration_minutes ?? '-' }} menit</li>
                                <li>Harga Rp {{ number_format($treatment->price, 0, ',', '.') }}</li>
                                <li>Reservasi online tersedia</li>
                            </ul>

                            <a href="{{ route('treatments.index', ['selected' => $treatment->id]) }}#booking-form" class="btn btn-outline">
                                Book This Treatment
                            </a>
                        </article>
                    @empty
                        <article class="treatment-card reveal">
                            <div class="treatment-badge">Treatment</div>
                            <h3>Belum ada treatment unggulan</h3>
                            <p>Silakan tambahkan treatment melalui halaman admin.</p>
                            <a href="{{ route('treatments.index') }}" class="btn btn-outline">View Treatments</a>
                        </article>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="promotions section-block" id="promotion">
            <div class="container promo-layout">
                <div class="section-heading reveal">
                    <span class="eyebrow eyebrow-dark">Special Offers</span>

                    <h2>
                        @auth
                            Exclusive promotions for Lumiere members
                        @else
                            Limited promotions for guests
                        @endauth
                    </h2>

                    <p>
                        @auth
                            As a member, you can access more promotions based on your membership level.
                        @else
                            Login or register as a member to unlock more promotions and point benefits.
                        @endauth
                    </p>
                </div>

                <div class="promo-grid">
                    @forelse($promotions as $promotion)
                        <article class="promotion-card reveal {{ $promotion->membership_only ? 'accent-card' : '' }}">
                            <span class="promo-tag">
                                {{ $promotion->membership_only ? 'Member Promotion' : 'Public Promotion' }}
                            </span>

                            <h3>{{ $promotion->title }}</h3>

                            <p>{{ $promotion->description }}</p>

                            <p class="promotion-discount">
                                {{ number_format($promotion->discount_percent, 0) }}% OFF
                            </p>

                            @if($promotion->promo_code)
                                <p>
                                    Code:
                                    <strong>{{ $promotion->promo_code }}</strong>
                                </p>
                            @endif

                            <a href="{{ route('promotions.index') }}" class="btn btn-primary">
                                View Promotion
                            </a>
                        </article>
                    @empty
                        <article class="promotion-card reveal">
                            <span class="promo-tag">Promotion</span>
                            <h3>No active promotions yet</h3>
                            <p>Promotions will appear after they are added from the admin page.</p>                            <a href="{{ route('promotions.index') }}" class="btn btn-primary">
                                View Promotion
                            </a>
                        </article>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="membership section-block" id="membership">
            <div class="container membership-layout">
                <div class="section-heading reveal">
                    <span class="eyebrow eyebrow-dark">Lumiere Membership</span>

                    <h2>Semakin sering treatment, semakin tinggi level membership-mu</h2>

                    <p>
                        Customer yang login sebagai member akan mendapatkan point dari setiap treatment.
                        These points will increase the membership level and unlock exclusive promotions.                    </p>
                </div>

                <div class="membership-grid">
                    <article class="membership-card reveal">
                        <span class="member-badge bronze">Bronze</span>
                        <h3>Bronze Member</h3>
                        <p>Level awal untuk semua customer yang sudah membuat akun membership.</p>
                        <strong>0 Points</strong>
                    </article>

                    <article class="membership-card reveal">
                        <span class="member-badge silver">Silver</span>
                        <h3>Silver Member</h3>
                        <p>Get access to additional promotions and selected treatment offers.</p>
                        <strong>500 Points</strong>
                    </article>

                    <article class="membership-card reveal">
                        <span class="member-badge gold">Gold</span>
                        <h3>Gold Member</h3>
                        <p>Get exclusive promotions and more benefits than regular members.</p>
                        <strong>1500 Points</strong>
                    </article>

                    <article class="membership-card reveal">
                        <span class="member-badge platinum">Platinum</span>
                        <h3>Platinum Member</h3>
                        <p>The highest level with premium rewards and priority treatment promotions.</p>
                        <strong>3000 Points</strong>
                    </article>
                </div>

                <div class="membership-cta reveal">
                    @auth
                        <a href="{{ route('profile.index') }}" class="btn btn-primary">
                            View My Membership
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            Join Membership
                        </a>

                        <a href="{{ route('login') }}" class="btn btn-outline">
                            Login Member
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </main>
@endsection