@extends('layouts.app')

@section('title', 'Pembayaran Treatment - Lumiere Beauty')

@section('content')
    <section class="section py-5">
        <div class="container">
            <div class="form-card card shadow-sm border-0 mx-auto" style="width:min(760px, 92%);">
                <div class="card-body p-4 p-md-5">
                    <h2 class="text-center mb-2">Pembayaran Treatment</h2>
                    <p class="text-center mb-4">
                        Selesaikan instruksi pembayaran berikut, lalu kirim konfirmasi.
                    </p>

                    {{-- Ringkasan treatment dan booking --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-3">{{ $treatment->treatment_name }}</h3>
                            <p class="card-text">{{ $treatment->short_description }}</p>

                            <p class="mb-3">
                                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pending['booking_date'])->format('d M Y') }}<br>
                                <strong>Jam:</strong> {{ $pending['booking_time'] }}<br>
                                <strong>Metode:</strong> {{ $paymentLabels[$pending['payment_method']] ?? $pending['payment_method'] }}
                            </p>

                            <div class="price fw-bold">
                                Total: Rp {{ number_format($treatment->price, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    {{-- Instruksi pembayaran QRIS --}}
                    @if($pending['payment_method'] === 'qris')
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-4 text-center">
                                <h3 class="card-title mb-3">Instruksi QRIS</h3>
                                <p>
                                    Scan QRIS berikut menggunakan aplikasi mobile banking atau e-wallet Anda.
                                </p>

                                <img
                                    src="{{ asset($qrisImage) }}"
                                    alt="QRIS Lumiere Beauty"
                                    class="img-fluid rounded-4 shadow-sm my-3"
                                    style="max-width:260px; height:auto;"
                                >
                            </div>
                        </div>
                    @endif

                    {{-- Instruksi pembayaran bank transfer --}}
                    @if($pending['payment_method'] === 'bank_transfer')
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-4">
                                <h3 class="card-title mb-3">Instruksi Bank Transfer</h3>

                                @foreach($bankAccounts as $account)
                                    <div class="mb-3">
                                        <p class="mb-0">
                                            <strong>{{ $account['bank'] }}</strong><br>
                                            Nomor Rekening: {{ $account['number'] }}<br>
                                            Atas Nama: {{ $account['name'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Instruksi pembayaran e-wallet --}}
                    @if($pending['payment_method'] === 'e_wallet')
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-4">
                                <h3 class="card-title mb-3">Instruksi E-Wallet</h3>

                                @foreach($eWalletAccounts as $account)
                                    <div class="mb-3">
                                        <p class="mb-0">
                                            <strong>{{ $account['provider'] }}</strong><br>
                                            Nomor: {{ $account['number'] }}<br>
                                            Atas Nama: {{ $account['name'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Instruksi pembayaran cash --}}
                    @if($pending['payment_method'] === 'cash')
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-4">
                                <h3 class="card-title mb-3">Pembayaran Cash</h3>
                                <p class="mb-0">
                                    Pembayaran dilakukan langsung di klinik. Anda tetap perlu mengirim konfirmasi
                                    agar booking tercatat dan menunggu verifikasi admin.
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Form konfirmasi pembayaran --}}
                    <form action="{{ route('checkout.confirm') }}" method="POST">
                        @csrf

                        <input type="hidden" name="payment_token" value="{{ $token }}">

                        <div class="form-group mb-3">
                            <label for="payer_name" class="form-label">Nama Pembayar</label>
                            <input
                                type="text"
                                id="payer_name"
                                name="payer_name"
                                value="{{ old('payer_name', auth()->user()->name) }}"
                                class="form-control @error('payer_name') is-invalid @enderror"
                                required
                            >

                            @error('payer_name')
                                <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="payment_reference" class="form-label">
                                Nomor Referensi atau Catatan Transaksi
                            </label>
                            <input
                                type="text"
                                id="payment_reference"
                                name="payment_reference"
                                value="{{ old('payment_reference') }}"
                                class="form-control @error('payment_reference') is-invalid @enderror"
                                placeholder="Opsional. Contoh: nomor transaksi, nama bank, atau akun e-wallet"
                            >

                            @error('payment_reference')
                                <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="payment_note" class="form-label">Catatan Pembayaran</label>
                            <textarea
                                id="payment_note"
                                name="payment_note"
                                rows="4"
                                class="form-control @error('payment_note') is-invalid @enderror"
                                placeholder="Tambahkan catatan pembayaran jika diperlukan"
                            >{{ old('payment_note') }}</textarea>

                            @error('payment_note')
                                <small class="form-error invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                Konfirmasi Pembayaran
                            </button>
                            <a href="{{ route('treatments.index') }}" class="btn btn-secondary flex-fill">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection