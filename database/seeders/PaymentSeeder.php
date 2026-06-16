<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(PaymentService::class);
        $admin = User::where('role', 'admin')->first();

        if (! $admin) {
            return;
        }

        auth()->setUser($admin);

        $pastBooking = Booking::where('status', 'checked_out')->first();
        if ($pastBooking) {
            $service->recordPayment($pastBooking, [
                'amount' => $pastBooking->total_amount,
                'method' => 'cash',
                'payment_date' => $pastBooking->actual_check_out_at ?? today(),
                'notes' => 'Settled at check-out',
            ]);
        }

        $checkedInBooking = Booking::where('status', 'checked_in')->first();
        if ($checkedInBooking) {
            $service->recordPayment($checkedInBooking, [
                'amount' => round($checkedInBooking->total_amount * 0.5, 2),
                'method' => 'card',
                'payment_date' => $checkedInBooking->actual_check_in_at ?? today(),
                'transaction_id' => 'AUTH-'.rand(100000, 999999),
                'notes' => '50% advance at check-in',
            ]);
        }

        $secondCheckedIn = Booking::where('status', 'checked_in')->skip(1)->first();
        if ($secondCheckedIn) {
            $service->recordPayment($secondCheckedIn, [
                'amount' => $secondCheckedIn->total_amount,
                'method' => 'bank_transfer',
                'payment_date' => $secondCheckedIn->actual_check_in_at ?? today(),
                'transaction_id' => 'TRF-'.rand(100000, 999999),
                'notes' => 'Paid in full upfront',
            ]);
        }
    }
}
