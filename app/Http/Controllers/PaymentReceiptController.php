<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class PaymentReceiptController extends Controller
{
    public function show(Booking $booking)
    {
        $booking->load(['user', 'treatment', 'payment']);

        $user = auth()->user();

        /*
         * Admin dapat melihat seluruh receipt.
         * Customer hanya dapat melihat receipt booking miliknya sendiri.
         *
         * ID diubah menjadi integer agar tidak bermasalah apabila
         * database hosting mengembalikan user_id sebagai string.
         */
        if (
            !$user->isAdmin() &&
            (int) $booking->user_id !== (int) $user->getKey()
        ) {
            abort(403);
        }

        if (!$booking->payment || $booking->payment_status !== 'paid') {
            return redirect()
                ->route('bookings.mine')
                ->with(
                    'error',
                    'Receipt hanya tersedia setelah pembayaran diverifikasi.'
                );
        }

        return view('payments.receipt', compact('booking'));
    }
}