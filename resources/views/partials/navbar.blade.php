{{-- Header utama website yang muncul di bagian atas halaman --}}
<header class="site-header" id="top">
    <div class="container header-inner">
        {{-- Brand/logo Lumière Beauty. Ketika diklik akan kembali ke homepage --}}
        <a href="{{ route('home') }}#home" class="brand brand-logo-link" aria-label="Lumière Beauty Clinic">
            <img
                src="{{ asset('assets/images/logo-lumiere.png') }}"
                alt="Lumière Beauty Clinic Logo"
                class="brand-logo-img"
            >
        </a>

        {{-- Tombol menu untuk tampilan mobile/responsive --}}
        <button class="menu-toggle" id="menuToggle" aria-label="Buka menu" aria-expanded="false" aria-controls="siteNav">
            <span></span><span></span><span></span>
        </button>

        {{-- Navigasi utama website --}}
        <nav class="site-nav" id="siteNav" aria-label="Navigasi utama">
            <a href="{{ route('home') }}#home" class="nav-link active">Homepage</a>
            <a href="{{ route('treatments.index') }}" class="nav-link">Treatments</a>
            <a href="{{ route('promotions.index') }}" class="nav-link">Promotion</a>
            <a href="{{ route('home') }}#membership" class="nav-link">Membership</a>
            <a href="{{ route('home') }}#contact" class="nav-link">Contact</a>

            {{-- Menu tambahan khusus mobile, muncul di dalam hamburger --}}
            <div class="d-lg-none mt-3">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            Admin Dashboard
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="nav-link"
                                style="background: none; border: 0; width: 100%; text-align: left;"
                            >
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('bookings.mine') }}" class="nav-link">
                            My Bookings
                        </a>

                        <a href="{{ route('points.index') }}" class="nav-link">
                            My Points
                        </a>

                        <a href="{{ route('profile.index') }}" class="nav-link">
                            Profile
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="nav-link"
                                style="background: none; border: 0; width: 100%; text-align: left;"
                            >
                                Logout
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="nav-link">
                        Login
                    </a>
                @endauth
            </div>
        </nav>

        {{-- Bagian tombol aksi di kanan navbar untuk desktop/tablet besar --}}
        <div class="header-actions">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="header-cta header-cta-light d-none d-lg-inline-flex">
                        Admin
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="logout-form d-none d-lg-block">
                        @csrf
                        <button type="submit" class="header-cta header-cta-light">
                            Logout
                        </button>
                    </form>
                @else
                    <details class="account-dropdown d-none d-lg-block">
                        <summary class="header-cta header-cta-light">
                            Account
                        </summary>

                        <div class="account-menu">
                            <a href="{{ route('bookings.mine') }}">My Bookings</a>
                            <a href="{{ route('points.index') }}">My Points</a>
                            <a href="{{ route('profile.index') }}">Profile</a>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">Logout</button>
                            </form>
                        </div>
                    </details>
                @endif
            @else
                <a href="{{ route('login') }}" class="header-cta header-cta-light d-none d-lg-inline-flex">
                    Login
                </a>
            @endauth

            {{-- Tombol utama tetap tampil di desktop dan mobile --}}
            <a href="{{ route('treatments.index') }}" class="header-cta">
                Book Now
            </a>
        </div>
    </div>
</header>