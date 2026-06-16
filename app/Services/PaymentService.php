<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;

class PaymentService
{
    public function generateReference(): string
    {
        $year = now()->year;
        $last = Payment::where('payment_reference', 'like', "PAY-{$year}-%")
            ->orderByDesc('id')
            ->first();

        $next = 1;
        if ($last) {
            $parts = explode('-', $last->payment_reference);
            $next = (int) end($parts) + 1;
        }

        return sprintf('PAY-%d-%05d', $year, $next);
    }

    public function recordPayment(Booking $booking, array $data): Payment
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($booking, $data) {
            $payment = Payment::create(array_merge($data, [
                'payment_reference' => $this->generateReference(),
                'booking_id' => $booking->id,
                'guest_id' => $booking->guest_id,
                'type' => 'payment',
                'received_by' => auth()->id(),
                'payment_date' => $data['payment_date'] ?? today(),
            ]));

            $this->updateBookingPaymentStatus($booking);

            AuditLogger::log(
                'payment.recorded',
                $payment,
                'Payment of '.formatPKR($payment->amount)." recorded for booking {$booking->booking_reference}"
            );

            return $payment;
        });
    }

    public function recordRefund(Booking $booking, array $data): Payment
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($booking, $data) {
            $refund = Payment::create(array_merge($data, [
                'payment_reference' => $this->generateReference(),
                'booking_id' => $booking->id,
                'guest_id' => $booking->guest_id,
                'type' => 'refund',
                'received_by' => auth()->id(),
                'payment_date' => $data['payment_date'] ?? today(),
            ]));

            $this->updateBookingPaymentStatus($booking);

            AuditLogger::log(
                'payment.refunded',
                $refund,
                'Refund of '.formatPKR($refund->amount)." issued for booking {$booking->booking_reference}"
            );

            return $refund;
        });
    }

    public function voidPayment(Payment $payment, string $reason): Payment
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($payment, $reason) {
            $payment->update([
                'voided_at' => now(),
                'void_reason' => $reason,
            ]);

            $this->updateBookingPaymentStatus($payment->booking);

            AuditLogger::log(
                'payment.voided',
                $payment,
                "Payment {$payment->payment_reference} voided. Reason: {$reason}"
            );

            return $payment->fresh();
        });
    }

    public function updateBookingPaymentStatus(Booking $booking): void
    {
        $paid = (float) $booking->payments()->where('type', 'payment')->sum('amount');
        $refunded = (float) $booking->payments()->where('type', 'refund')->sum('amount');
        $net = $paid - $refunded;
        $total = (float) $booking->total_amount;

        if ($net <= 0) {
            $status = 'unpaid';
        } elseif ($net >= $total) {
            $status = 'paid';
        } else {
            $status = 'partial';
        }

        $booking->update(['payment_status' => $status]);
    }
}
