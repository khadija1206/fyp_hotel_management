<?php

namespace Tests\Feature\Receptionist;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::create(['key' => 'tax_rate', 'value' => '13', 'type' => 'number', 'group' => 'billing', 'label' => 'Tax']);
    }

    public function test_receptionist_can_create_a_booking(): void
    {
        $user = User::factory()->create(['role' => 'receptionist']);
        $type = RoomType::create(['name' => 'Test', 'capacity' => 2, 'bed_count' => 1, 'bed_layout' => 'double', 'base_price' => 5000]);
        $room = Room::create(['room_number' => '101', 'room_type_id' => $type->id, 'floor' => 1, 'price_per_night' => 5000, 'status' => 'available']);
        $guest = Guest::create(['first_name' => 'Test', 'last_name' => 'Guest', 'phone' => '03001234567', 'nationality' => 'Pakistani', 'country' => 'Pakistan']);

        $this->actingAs($user)->post('/bookings', [
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in_date' => Carbon::tomorrow()->toDateString(),
            'check_out_date' => Carbon::tomorrow()->addDays(2)->toDateString(),
            'num_guests' => 2,
        ]);

        $this->assertDatabaseHas('bookings', ['guest_id' => $guest->id, 'room_id' => $room->id, 'status' => 'confirmed']);
        $this->assertEquals('reserved', $room->fresh()->status);
    }

    public function test_check_in_changes_room_to_occupied(): void
    {
        $user = User::factory()->create(['role' => 'receptionist']);
        $type = RoomType::create(['name' => 'Test', 'capacity' => 2, 'bed_count' => 1, 'bed_layout' => 'double', 'base_price' => 5000]);
        $room = Room::create(['room_number' => '101', 'room_type_id' => $type->id, 'floor' => 1, 'price_per_night' => 5000, 'status' => 'reserved']);
        $guest = Guest::create(['first_name' => 'Test', 'last_name' => 'G', 'phone' => '03001234567', 'nationality' => 'Pakistani', 'country' => 'Pakistan']);

        $booking = Booking::create([
            'booking_reference' => 'BK-TEST-1', 'guest_id' => $guest->id, 'room_id' => $room->id,
            'check_in_date' => today(), 'check_out_date' => today()->addDay(),
            'num_nights' => 1, 'num_guests' => 1,
            'rate_per_night' => 5000, 'subtotal' => 5000, 'tax_rate' => 13, 'tax_amount' => 650, 'total_amount' => 5650,
            'status' => 'confirmed',
        ]);

        $this->actingAs($user)->post("/check-in/{$booking->id}");

        $this->assertEquals('checked_in', $booking->fresh()->status);
        $this->assertEquals('occupied', $room->fresh()->status);
    }

    public function test_check_out_releases_room(): void
    {
        $user = User::factory()->create(['role' => 'receptionist']);
        $type = RoomType::create(['name' => 'T', 'capacity' => 2, 'bed_count' => 1, 'bed_layout' => 'double', 'base_price' => 5000]);
        $room = Room::create(['room_number' => '101', 'room_type_id' => $type->id, 'floor' => 1, 'price_per_night' => 5000, 'status' => 'occupied']);
        $guest = Guest::create(['first_name' => 'Test', 'last_name' => 'G', 'phone' => '03001234567', 'nationality' => 'Pakistani', 'country' => 'Pakistan']);

        $booking = Booking::create([
            'booking_reference' => 'BK-TEST-2', 'guest_id' => $guest->id, 'room_id' => $room->id,
            'check_in_date' => today()->subDay(), 'check_out_date' => today(),
            'actual_check_in_at' => now()->subDay(),
            'num_nights' => 1, 'num_guests' => 1,
            'rate_per_night' => 5000, 'subtotal' => 5000, 'tax_rate' => 13, 'tax_amount' => 650, 'total_amount' => 5650,
            'status' => 'checked_in',
        ]);

        $this->actingAs($user)->post("/check-out/{$booking->id}");

        $this->assertEquals('checked_out', $booking->fresh()->status);
        $this->assertEquals('available', $room->fresh()->status);
    }
}
