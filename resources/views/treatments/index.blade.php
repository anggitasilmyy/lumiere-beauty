@extends('layouts.app')

@section('title', 'Treatments - Lumiere Beauty Clinic')

@section('content')
<main>
    <section class="page-hero">
        <div class="container page-hero-content">
            <span class="eyebrow eyebrow-dark">Our Treatments</span>
            <h1>Choose Your Beauty Treatment</h1>
            <p>
                Pilih treatment yang sesuai dengan kebutuhan kulitmu,
                tentukan jadwal kunjungan, lalu lanjutkan ke proses pembayaran.
            </p>
        </div>
    </section>

    <section class="section-block">
        <div class="container">
            <form action="{{ route('treatments.index') }}"
                  method="GET"
                  class="filter-panel reveal">

                @if(filled($promoCode))
                    <input type="hidden" name="promo" value="{{ $promoCode }}">
                @endif

                <div class="filter-group mb-3">
                    <label for="search" class="form-label">
                        Cari Treatment
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Masukkan nama treatment"
                    >
                </div>

                <div class="filter-group mb-3">
                    <label for="category" class="form-label">
                        Kategori
                    </label>

                    <select id="category"
                            name="category"
                            class="form-select">

                        <option value="">Semua Kategori</option>

                        @foreach($categories as $category)
                            <option
                                value="{{ $category }}"
                                {{ request('category') === $category
                                    ? 'selected'
                                    : '' }}
                            >
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        Filter
                    </button>

                    <a
                        href="{{ route(
                            'treatments.index',
                            filled($promoCode)
                                ? ['promo' => $promoCode]
                                : []
                        ) }}"
                        class="btn btn-outline btn-outline-secondary"
                    >
                        Reset
                    </a>
                </div>
            </form>

            <div class="treatment-grid treatment-list-grid">
                @forelse($treatments as $treatment)
                    <article class="treatment-card reveal h-100">
                        <div class="treatment-badge">
                            {{ $treatment->category ?? 'Treatment' }}
                        </div>

                        @if($treatment->image)
                            <div class="treatment-image-wrap">
                                <img
                                    src="{{ str_starts_with(
                                        $treatment->image,
                                        'assets/'
                                    )
                                        ? asset($treatment->image)
                                        : asset(
                                            'storage/' .
                                            $treatment->image
                                        ) }}"
                                    alt="{{ $treatment->treatment_name }}"
                                    class="treatment-image img-fluid"
                                >
                            </div>
                        @endif

                        <h3>{{ $treatment->treatment_name }}</h3>

                        <p>{{ $treatment->short_description }}</p>

                        <ul class="mb-3">
                            <li>
                                Durasi
                                {{ $treatment->duration_minutes ?? '-' }}
                                menit
                            </li>

                            <li>
                                Harga Rp
                                {{ number_format(
                                    $treatment->price,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </li>

                            <li>
                                Status tersedia untuk booking online
                            </li>
                        </ul>

                        <a
                            href="{{ route(
                                'treatments.index',
                                array_merge(
                                    request()->query(),
                                    ['selected' => $treatment->id]
                                )
                            ) }}#booking-form"
                            class="btn btn-primary"
                        >
                            Pilih Treatment
                        </a>
                    </article>
                @empty
                    <article class="treatment-card reveal">
                        <div class="treatment-badge">
                            Treatment
                        </div>

                        <h3>Data treatment belum tersedia</h3>

                        <p>
                            Tidak ada treatment yang sesuai dengan filter.
                        </p>
                    </article>
                @endforelse
            </div>

            <div class="pagination-wrap mt-4">
                {{ $treatments->links() }}
            </div>
        </div>
    </section>

    <section class="section-block booking-section"
             id="booking-form">

        <div class="container">
            <div class="booking-layout">
                <div class="section-heading reveal">
                    <span class="eyebrow eyebrow-dark">
                        Online Reservation
                    </span>

                    <h2>Booking Treatment</h2>

                    <p>
                        Setelah memilih treatment, isi tanggal, jam,
                        metode pembayaran, dan catatan tambahan.
                    </p>
                </div>

                <div class="booking-card reveal">
                    @auth
                        @if($selectedTreatment)
                            <div class="selected-treatment-box">
                                <span class="promo-tag">
                                    Selected Treatment
                                </span>

                                <h3>
                                    {{ $selectedTreatment->treatment_name }}
                                </h3>

                                <p>
                                    {{ $selectedTreatment->short_description }}
                                </p>

                                <strong>
                                    Rp
                                    {{ number_format(
                                        $selectedTreatment->price,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </strong>
                            </div>

                            <form
                                action="{{ route('checkout.store') }}"
                                method="POST"
                                class="booking-form"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="treatment_id"
                                    value="{{ $selectedTreatment->id }}"
                                >

                                <div class="form-grid">
                                    <div class="form-group mb-3">
                                        <label
                                            for="booking_date"
                                            class="form-label"
                                        >
                                            Tanggal Booking
                                        </label>

                                        <input
                                            type="date"
                                            id="booking_date"
                                            name="booking_date"
                                            value="{{ old('booking_date') }}"
                                            min="{{ now()->toDateString() }}"
                                            class="form-control
                                                @error('booking_date')
                                                    is-invalid
                                                @enderror"
                                            required
                                        >

                                        @error('booking_date')
                                            <small class="form-error invalid-feedback d-block">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label
                                            for="booking_time"
                                            class="form-label"
                                        >
                                            Jam Booking
                                        </label>

                                        <select
                                            id="booking_time"
                                            name="booking_time"
                                            class="form-select
                                                @error('booking_time')
                                                    is-invalid
                                                @enderror"
                                            required
                                        >
                                            <option value="">
                                                Pilih jam booking
                                            </option>

                                            @foreach(range(540, 1200, 30) as $minutes)
                                                @php
                                                    $time = sprintf(
                                                        '%02d:%02d',
                                                        intdiv($minutes, 60),
                                                        $minutes % 60
                                                    );
                                                @endphp

                                                <option
                                                    value="{{ $time }}"
                                                    {{ old('booking_time') === $time
                                                        ? 'selected'
                                                        : '' }}
                                                >
                                                    {{ str_replace(
                                                        ':',
                                                        '.',
                                                        $time
                                                    ) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('booking_time')
                                            <small class="form-error invalid-feedback d-block">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label
                                        for="payment_method"
                                        class="form-label"
                                    >
                                        Metode Pembayaran
                                    </label>

                                    <select
                                        id="payment_method"
                                        name="payment_method"
                                        class="form-select
                                            @error('payment_method')
                                                is-invalid
                                            @enderror"
                                        required
                                    >
                                        <option value="">
                                            Pilih metode pembayaran
                                        </option>

                                        @foreach($paymentMethods as $method => $label)
                                            <option
                                                value="{{ $method }}"
                                                {{ old('payment_method') === $method
                                                    ? 'selected'
                                                    : '' }}
                                            >
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('payment_method')
                                        <small class="form-error invalid-feedback d-block">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label
                                        for="promo_code"
                                        class="form-label"
                                    >
                                        Kode Promo
                                    </label>

                                    <input
                                        type="text"
                                        id="promo_code"
                                        name="promo_code"
                                        value="{{ old(
                                            'promo_code',
                                            $promoCode
                                        ) }}"
                                        class="form-control text-uppercase
                                            @error('promo_code')
                                                is-invalid
                                            @enderror"
                                        placeholder="Contoh: FIRSTGLOW"
                                    >

                                    <small class="form-text text-muted">
                                        Kosongkan jika tidak menggunakan
                                        promo. Diskon akan diperiksa dan
                                        dihitung oleh sistem.
                                    </small>

                                    @error('promo_code')
                                        <small class="form-error invalid-feedback d-block">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label
                                        for="notes"
                                        class="form-label"
                                    >
                                        Catatan
                                    </label>

                                    <textarea
                                        id="notes"
                                        name="notes"
                                        rows="4"
                                        class="form-control
                                            @error('notes')
                                                is-invalid
                                            @enderror"
                                        placeholder="Catatan tambahan jika ada"
                                    >{{ old('notes') }}</textarea>

                                    @error('notes')
                                        <small class="form-error invalid-feedback d-block">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block w-100"
                                >
                                    Lanjut Pembayaran
                                </button>
                            </form>
                        @else
                            <div class="empty-state">
                                <h3>Belum ada treatment dipilih</h3>

                                <p>
                                    Silakan pilih salah satu treatment
                                    terlebih dahulu.
                                </p>
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <h3>Login diperlukan</h3>

                            <p>
                                Silakan login terlebih dahulu untuk
                                melakukan booking treatment.
                            </p>

                            <a
                                href="{{ route('login') }}"
                                class="btn btn-primary"
                            >
                                Login
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</main>
@endsection