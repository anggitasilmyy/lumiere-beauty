<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Treatment;
use App\Services\PaymentChannelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function storePending(Request $request)
    {
        $data = $request->validate([
            'treatment_id' => ['required', 'exists:treatments,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required'],
            'payment_method' => ['required', 'in:qris,bank_transfer,e_wallet,cash'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $treatment = Treatment::where('is_active', true)
            ->findOrFail($data['treatment_id']);

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
                'created_at' => now()->timestamp,
            ],
        ]);

        return redirect()->route('checkout.payment', $token)
            ->with('success', 'Silakan selesaikan instruksi pembayaran.');
    }

    public function payment(string $token, PaymentChannelService $paymentChannelService)
    {
        $pending = session('pending_treatment_payment');

        if (!$pending || !hash_equals($pending['token'], $token)) {
            abort(404);
        }

        if ((int) $pending['user_id'] !== auth()->id()) {
            abort(403);
        }

        if ((time() - $pending['created_at']) > 3600) {
            session()->forget('pending_treatment_payment');

            return redirect()->route('treatments.index')
                ->with('error', 'Sesi pembayaran sudah kedaluwarsa. Silakan booking ulang.');
        }

        $treatment = Treatment::findOrFail($pending['treatment_id']);

        $paymentLabels = $paymentChannelService->labels();
        $bankAccounts = $paymentChannelService->bankAccounts();
        $eWalletAccounts = $paymentChannelService->eWalletAccounts();
        $qrisImage = $paymentChannelService->qrisImage();

        return view('checkout.payment', compact(
            'pending',
            'treatment',
            'token',
            'paymentLabels',
            'bankAccounts',
            'eWalletAccounts',
            'qrisImage'
        ));
    }

    public function confirm(Request $request)
    {
        $data = $request->validate([
            'payment_token' => ['required', 'string'],
            'payer_name' => ['required', 'string', 'max:100'],
            'payment_note' => ['nullable', 'string', 'max:1000'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
        ]);

        $pending = session('pending_treatment_payment');

        if (!$pending || !hash_equals($pending['token'], $data['payment_token'])) {
            abort(404);
        }

        if ((int) $pending['user_id'] !== auth()->id()) {
            abort(403);
        }

        if ((time() - $pending['created_at']) > 3600) {
            session()->forget('pending_treatment_payment');

            return redirect()->route('treatments.index')
                ->with('error', 'Sesi pembayaran sudah kedaluwarsa. Silakan booking ulang.');
        }

        $treatment = Treatment::findOrFail($pending['treatment_id']);

        $booking = DB::transaction(function () use ($pending, $treatment, $data) {
            $booking = Booking::create([
                'booking_code' => $this->generateBookingCode(),
                'user_id' => auth()->id(),
                'treatment_id' => $treatment->id,
                'booking_date' => $pending['booking_date'],
                'booking_time' => $pending['booking_time'],
                'notes' => $pending['notes'],
                'total_price' => $treatment->price,
                'payment_status' => 'waiting_verification',
                'booking_status' => 'pending',
                'treatment_status' => 'not_started',
            ]);

            Payment::create([
                'booking_id' => $booking->id,
                'transaction_code' => $this->generateTransactionCode(),
                'receipt_code' => null,
                'payment_method' => $pending['payment_method'],
                'amount' => $treatment->price,
                'payment_status' => 'waiting_verification',
                'payer_name' => $data['payer_name'],
                'payment_reference' => $data['payment_reference'] ?? null,
                'payment_proof' => null,
                'payment_note' => $data['payment_note'] ?? null,
            ]);

            return $booking;
        });

        session()->forget('pending_treatment_payment');

        return redirect()->route('bookings.mine')
            ->with('success', 'Konfirmasi pembayaran berhasil dikirim. Booking Anda menunggu verifikasi admin.');
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'BK-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }

    private function generateTransactionCode(): string
    {
        do {
            $code = 'TRX-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
        } while (Payment::where('transaction_code', $code)->exists());

        return $code;
    }
}