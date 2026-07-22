<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Treatment;
use App\Services\PaymentChannelService;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function storePending(
        Request $request,
        PromotionService $promotionService
    ) {
        $allowedBookingTimes = $this->allowedBookingTimes();

        $data = $request->validate([
            'treatment_id' => [
                'required',
                'exists:treatments,id',
            ],

            'booking_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'booking_time' => [
                'required',
                'date_format:H:i',
                Rule::in($allowedBookingTimes),
            ],

            'payment_method' => [
                'required',
                'in:qris,bank_transfer,e_wallet,cash',
            ],

            'promo_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'treatment_id.required' =>
                'Treatment wajib dipilih.',

            'treatment_id.exists' =>
                'Treatment yang dipilih tidak tersedia.',

            'booking_date.required' =>
                'Tanggal booking wajib dipilih.',

            'booking_date.date' =>
                'Tanggal booking tidak valid.',

            'booking_date.after_or_equal' =>
                'Tanggal booking tidak boleh sebelum hari ini.',

            'booking_time.required' =>
                'Jam booking wajib dipilih.',

            'booking_time.date_format' =>
                'Format jam booking tidak valid.',

            'booking_time.in' =>
                'Jam booking hanya tersedia dari pukul 09.00 sampai 20.00 dengan interval 30 menit.',

            'payment_method.required' =>
                'Metode pembayaran wajib dipilih.',

            'payment_method.in' =>
                'Metode pembayaran tidak valid.',

            'promo_code.max' =>
                'Kode promo maksimal 50 karakter.',

            'notes.max' =>
                'Catatan maksimal 1.000 karakter.',
        ]);

        $treatment = Treatment::where('is_active', true)
            ->findOrFail($data['treatment_id']);

        $pricing = $promotionService->calculate(
            auth()->user(),
            $treatment,
            $data['promo_code'] ?? null
        );

        $token = Str::random(40);

        session([
            'pending_treatment_payment' => [
                'token' => $token,
                'user_id' => auth()->id(),
                'treatment_id' => $treatment->id,
                'booking_date' => $data['booking_date'],
                'booking_time' => $data['booking_time'],
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
                'promotion_id' => $pricing['promotion_id'] ?? null,
                'promotion_title' => $pricing['promotion_title'] ?? null,
                'promo_code' => $pricing['promo_code'] ?? null,
                'original_price' => $pricing['original_price'],
                'discount_percent' => $pricing['discount_percent'],
                'discount_amount' => $pricing['discount_amount'],
                'final_price' => $pricing['final_price'],
                'created_at' => now()->timestamp,
            ],
        ]);

        return redirect()
            ->route('checkout.payment', $token)
            ->with(
                'success',
                'Silakan selesaikan instruksi pembayaran.'
            );
    }

    public function payment(
        string $token,
        PaymentChannelService $paymentChannelService
    ) {
        $pending = session('pending_treatment_payment');

        if (
            !$pending ||
            !isset($pending['token']) ||
            !hash_equals(
                (string) $pending['token'],
                $token
            )
        ) {
            abort(404);
        }

        if (
            (int) ($pending['user_id'] ?? 0) !==
            (int) auth()->id()
        ) {
            abort(403);
        }

        if (
            !isset($pending['created_at']) ||
            (time() - (int) $pending['created_at']) > 3600
        ) {
            session()->forget(
                'pending_treatment_payment'
            );

            return redirect()
                ->route('treatments.index')
                ->with(
                    'error',
                    'Sesi pembayaran sudah kedaluwarsa. Silakan booking ulang.'
                );
        }

        $treatment = Treatment::findOrFail(
            $pending['treatment_id']
        );

        $paymentLabels =
            $paymentChannelService->labels();

        $bankAccounts =
            $paymentChannelService->bankAccounts();

        $eWalletAccounts =
            $paymentChannelService->eWalletAccounts();

        $qrisImage =
            $paymentChannelService->qrisImage();

        return view(
            'checkout.payment',
            compact(
                'pending',
                'treatment',
                'token',
                'paymentLabels',
                'bankAccounts',
                'eWalletAccounts',
                'qrisImage'
            )
        );
    }

    public function confirm(Request $request)
    {
        $data = $request->validate([
            'payment_token' => [
                'required',
                'string',
            ],

            'payer_name' => [
                'required',
                'string',
                'max:100',
            ],

            'payment_note' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'payment_reference' => [
                'nullable',
                'string',
                'max:100',
            ],
        ], [
            'payment_token.required' =>
                'Token pembayaran tidak tersedia.',

            'payer_name.required' =>
                'Nama pembayar wajib diisi.',

            'payer_name.max' =>
                'Nama pembayar maksimal 100 karakter.',

            'payment_note.max' =>
                'Catatan pembayaran maksimal 1.000 karakter.',

            'payment_reference.max' =>
                'Nomor referensi maksimal 100 karakter.',
        ]);

        $pending = session(
            'pending_treatment_payment'
        );

        if (
            !$pending ||
            !isset($pending['token']) ||
            !hash_equals(
                (string) $pending['token'],
                (string) $data['payment_token']
            )
        ) {
            abort(404);
        }

        if (
            (int) ($pending['user_id'] ?? 0) !==
            (int) auth()->id()
        ) {
            abort(403);
        }

        if (
            !isset($pending['created_at']) ||
            (time() - (int) $pending['created_at']) > 3600
        ) {
            session()->forget(
                'pending_treatment_payment'
            );

            return redirect()
                ->route('treatments.index')
                ->with(
                    'error',
                    'Sesi pembayaran sudah kedaluwarsa. Silakan booking ulang.'
                );
        }

        $treatment = Treatment::findOrFail(
            $pending['treatment_id']
        );

        DB::transaction(function () use (
            $pending,
            $treatment,
            $data
        ) {
            $booking = Booking::create([
                'booking_code' =>
                    $this->generateBookingCode(),

                'user_id' =>
                    auth()->id(),

                'treatment_id' =>
                    $treatment->id,

                'promotion_id' =>
                    $pending['promotion_id'] ?? null,

                'promo_code' =>
                    $pending['promo_code'] ?? null,

                'booking_date' =>
                    $pending['booking_date'],

                'booking_time' =>
                    $pending['booking_time'],

                'notes' =>
                    $pending['notes'] ?? null,

                'original_price' =>
                    $pending['original_price'],

                'discount_percent' =>
                    $pending['discount_percent'],

                'discount_amount' =>
                    $pending['discount_amount'],

                'total_price' =>
                    $pending['final_price'],

                'payment_status' =>
                    'waiting_verification',

                'booking_status' =>
                    'pending',

                'treatment_status' =>
                    'not_started',
            ]);

            Payment::create([
                'booking_id' =>
                    $booking->id,

                'transaction_code' =>
                    $this->generateTransactionCode(),

                'receipt_code' =>
                    null,

                'payment_method' =>
                    $pending['payment_method'],

                'amount' =>
                    $pending['final_price'],

                'payment_status' =>
                    'waiting_verification',

                'payer_name' =>
                    $data['payer_name'],

                'payment_reference' =>
                    $data['payment_reference'] ?? null,

                'payment_proof' =>
                    null,

                'payment_note' =>
                    $data['payment_note'] ?? null,
            ]);
        });

        session()->forget(
            'pending_treatment_payment'
        );

        return redirect()
            ->route('bookings.mine')
            ->with(
                'success',
                'Konfirmasi pembayaran berhasil dikirim. Booking Anda menunggu verifikasi admin.'
            );
    }

    /**
     * Jam booking yang diperbolehkan:
     * 09.00 sampai 20.00 dengan interval 30 menit.
     */
    private function allowedBookingTimes(): array
    {
        return [
            '09:00',
            '09:30',
            '10:00',
            '10:30',
            '11:00',
            '11:30',
            '12:00',
            '12:30',
            '13:00',
            '13:30',
            '14:00',
            '14:30',
            '15:00',
            '15:30',
            '16:00',
            '16:30',
            '17:00',
            '17:30',
            '18:00',
            '18:30',
            '19:00',
            '19:30',
            '20:00',
        ];
    }

    private function generateBookingCode(): string
    {
        do {
            $code =
                'BK-' .
                now()->format('YmdHis') .
                '-' .
                random_int(1000, 9999);
        } while (
            Booking::where(
                'booking_code',
                $code
            )->exists()
        );

        return $code;
    }

    private function generateTransactionCode(): string
    {
        do {
            $code =
                'TRX-' .
                now()->format('YmdHis') .
                '-' .
                random_int(1000, 9999);
        } while (
            Payment::where(
                'transaction_code',
                $code
            )->exists()
        );

        return $code;
    }
}