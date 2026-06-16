<?php

namespace Tests\Feature\Receptionist;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Setting;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private function setupBooking(): Booking
    {
        Setting::create(['key' => 'tax_rate', 'value' => '13', 'type' => 'number', 'group' => 'billing', 'label' => 'Tax']);
        $type = RoomType::create(['name' => 'T', 'capacity' => 2, 'bed_count' => 1, 'bed_layout' => 'double', 'base_price' => 5000]);
        $room = Room::create(['room_number' => '101', 'room_type_id' => $type->id, 'floor' => 1, 'price_per_night' => 5000, 'status' => 'occupied']);
        $guest = Guest::create(['first_name' => 'T', 'last_name' => 'G', 'phone' => '1', 'nationality' => 'X', 'country' => 'Y']);

        return Booking::create([
            'booking_reference' => 'BK-T-1', 'guest_id' => $guest->id, 'room_id' => $room->id,
            'check_in_date' => today(), 'check_out_date' => today()->addDays(2),
            'num_nights' => 2, 'num_guests' => 1,
            'rate_per_night' => 5000, 'subtotal' => 10000, 'tax_rate' => 13, 'tax_amount' => 1300, 'total_amount' => 11300,
            'status' => 'checked_in', 'payment_status' => 'unpaid',
        ]);
    }

    public function test_partial_payment_marks_booking_partial(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->setupBooking();

        $this->actingAs($admin)->post("/bookings/{$booking->id}/payment", [
            'amount' => 5000,
            'method' => 'cash',
            'payment_date' => today()->toDateString(),
        ]);

        $this->assertEquals('partial', $booking->fresh()->payment_status);
        $this->assertEquals(5000, $booking->fresh()->amount_paid);
        $this->assertEquals(6300, $booking->fresh()->amount_due);
    }

    public function test_full_payment_marks_booking_paid(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->setupBooking();

        $this->actingAs($admin)->post("/bookings/{$booking->id}/payment", [
            'amount' => 11300,
            'method' => 'card',
            'payment_date' => today()->toDateString(),
        ]);

        $this->assertEquals('paid', $booking->fresh()->payment_status);
    }

    public function test_overpayment_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->setupBooking();

        $this->actingAs($admin)->post("/bookings/{$booking->id}/payment", [
            'amount' => 999999,
            'method' => 'cash',
            'payment_date' => today()->toDateString(),
        ]);

        $this->assertDatabaseCount('payments', 0);
    }

    public function test_refund_reduces_paid_amount(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->setupBooking();

        auth()->setUser($admin);
        $service = app(PaymentService::class);
        $service->recordPayment($booking, ['amount' => 11300, 'method' => 'cash', 'payment_date' => today()]);

        $this->assertEquals('paid', $booking->fresh()->payment_status);

        $this->actingAs($admin)->post("/bookings/{$booking->id}/refund", [
            'amount' => 5000,
            'method' => 'cash',
            'payment_date' => today()->toDateString(),
            'notes' => 'Test refund',
        ]);

        $this->assertEquals('partial', $booking->fresh()->payment_status);
        $this->assertEquals(6300, $booking->fresh()->amount_paid);
    }

    public function test_voided_payment_does_not_count(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->setupBooking();

        auth()->setUser($admin);
        $service = app(PaymentService::class);
        $payment = $service->recordPayment($booking, ['amount' => 5000, 'method' => 'cash', 'payment_date' => today()]);

        $this->assertEquals('partial', $booking->fresh()->payment_status);

        $this->actingAs($admin)->put("/payments/{$payment->id}/void", ['void_reason' => 'Test void']);

        $this->assertEquals('unpaid', $booking->fresh()->payment_status);
    }
}
