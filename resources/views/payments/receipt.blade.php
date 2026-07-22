@extends('layouts.app')

@section('title', 'Payment Receipt - Lumiere Beauty Clinic')

@section('content')
    <style>
        .receipt-wrapper {
            max-width: 960px;
            margin: 0 auto;
        }

        .receipt-card-clean {
            background: #ffffff;
            border: 1px solid #f5d8e4;
            border-radius: 28px;
            box-shadow: 0 18px 45px rgba(133, 57, 91, 0.08);
            padding: 40px;
        }

        .receipt-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #f1d9e4;
            margin-bottom: 28px;
        }

        .receipt-top h2 {
            margin-bottom: 6px;
        }

        .receipt-status {
            text-transform: capitalize;
            white-space: nowrap;
        }

        .receipt-info-box {
            height: 100%;
            background: #fff;
            border: 1px solid #f5d8e4;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 28px rgba(133, 57, 91, 0.06);
        }

        .receipt-info-box h3 {
            font-size: 1.2rem;
            margin-bottom: 18px;
        }

        .receipt-detail-row {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .receipt-detail-row span {
            min-width: 135px;
            font-weight: 700;
            color: #4b1730;
        }

        .receipt-detail-row strong {
            flex: 1;
            font-weight: 500;
            word-break: break-word;
        }

        .receipt-total-clean {
            margin-top: 28px;
            background: #fff1f6;
            border: 1px solid #f5d8e4;
            border-radius: 20px;
            padding: 22px 26px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
        }

        .receipt-total-clean span {
            font-weight: 600;
        }

        .receipt-total-clean strong {
            font-size: 1.8rem;
            color: #c73578;
        }

        .receipt-actions-clean {
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        @media (max-width: 768px) {
            .receipt-card-clean {
                padding: 24px;
            }

            .receipt-top {
                flex-direction: column;
            }

            .receipt-detail-row {
                flex-direction: column;
                gap: 2px;
            }

            .receipt-detail-row span {
                min-width: auto;
            }

            .receipt-total-clean {
                flex-direction: column;
                align-items: flex-start;
            }

            .receipt-actions-clean {
                flex-direction: column;
            }

            .receipt-actions-clean .btn {
                width: 100%;
            }
        }

        @media print {
            .site-header,
            .navbar,
            .footer,
            .page-hero,
            .receipt-actions-clean {
                display: none !important;
            }

            .receipt-card-clean {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            body {
                background: #fff !important;
            }
        }
    </style>

    <main>
        <section class="page-hero">
            <div class="container page-hero-content">
                <span class="eyebrow eyebrow-dark">Payment Receipt</span>
                <h1>Receipt Pembayaran</h1>
                <p>Bukti pembayaran treatment setelah diverifikasi oleh admin.</p>
            </div>
        </section>

        <section class="section-block">
            <div class="container">
                <div class="receipt-wrapper">
                    <div class="receipt-card-clean reveal">
                        <div class="receipt-top">
                            <div>
                                <h2>Lumiere Beauty Clinic</h2>
                                <p class="mb-0">Official Payment Receipt</p>
                            </div>

                            <div>
                                <span class="status-pill status-paid badge bg-success receipt-status">
                                    {{ str_replace('_', ' ', $booking->payment_status) }}
                                </span>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="receipt-info-box">
                                    <h3>Receipt Information</h3>

                                    <div class="receipt-detail-row">
                                        <span>Receipt Code</span>
                                        <strong>{{ $booking->payment->receipt_code ?? '-' }}</strong>
                                    </div>

                                    <div class="receipt-detail-row">
                                        <span>Booking Code</span>
                                        <strong>{{ $booking->booking_code }}</strong>
                                    </div>

                                    <div class="receipt-detail-row">
                                        <span>Transaction Code</span>
                                        <strong>{{ $booking->payment->transaction_code ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="receipt-info-box">
                                    <h3>Customer</h3>

                                    <div class="receipt-detail-row">
                                        <span>Nama</span>
                                        <strong>{{ $booking->user->name ?? '-' }}</strong>
                                    </div>

                                    <div class="receipt-detail-row">
                                        <span>Email</span>
                                        <strong>{{ $booking->user->email ?? '-' }}</strong>
                                    </div>

                                    <div class="receipt-detail-row">
                                        <span>Telepon</span>
                                        <strong>{{ $booking->user->phone ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="receipt-info-box">
                                    <h3>Treatment</h3>

                                    <div class="receipt-detail-row">
                                        <span>Treatment</span>
                                        <strong>{{ $booking->treatment->treatment_name ?? '-' }}</strong>
                                    </div>

                                    <div class="receipt-detail-row">
                                        <span>Tanggal</span>
                                        <strong>{{ $booking->booking_date?->format('d M Y') }}</strong>
                                    </div>

                                    <div class="receipt-detail-row">
                                        <span>Jam</span>
                                        <strong>{{ $booking->booking_time }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="receipt-info-box">
                                    <h3>Payment</h3>

                                    <div class="receipt-detail-row">
                                        <span>Metode</span>
                                        <strong>
                                            {{ $booking->payment ? str_replace('_', ' ', $booking->payment->payment_method) : '-' }}
                                        </strong>
                                    </div>

                                    <div class="receipt-detail-row">
                                        <span>Payer</span>
                                        <strong>{{ $booking->payment->payer_name ?? '-' }}</strong>
                                    </div>

                                    <div class="receipt-detail-row">
                                        <span>Reference</span>
                                        <strong>{{ $booking->payment->payment_reference ?? '-' }}</strong>
                                    </div>

                                    <div class="receipt-detail-row">
                                        <span>Paid At</span>
                                        <strong>{{ $booking->payment?->paid_at?->format('d M Y H:i') ?? '-' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="receipt-total-clean">
                            <span>Total Payment</span>
                            <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                        </div>

                        <div class="receipt-actions-clean">
                            <a href="{{ route('bookings.mine') }}" class="btn btn-outline btn-outline-secondary">
                                Kembali
                            </a>
                            <button onclick="window.print()" class="btn btn-primary">
                                Print Receipt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection