<?php

namespace Tests\Feature\Guest;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_their_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'guest']);
        Guest::create([
            'user_id' => $user->id,
            'first_name' => 'Test', 'last_name' => 'User',
            'phone' => '03001234567', 'nationality' => 'Pakistani', 'country' => 'Pakistan',
        ]);

        $response = $this->actingAs($user)->get('/portal');

        $response->assertOk();
        $response->assertSee('Welcome back, Test');
    }

    public function test_guest_cannot_see_other_guests_bookings(): void
    {
        $user1 = User::factory()->create(['role' => 'guest']);
        $user2 = User::factory()->create(['role' => 'guest']);

        $guest1 = Guest::create(['user_id' => $user1->id, 'first_name' => 'A', 'last_name' => 'A', 'phone' => '1', 'nationality' => 'X', 'country' => 'Y']);
        $guest2 = Guest::create(['user_id' => $user2->id, 'first_name' => 'B', 'last_name' => 'B', 'phone' => '2', 'nationality' => 'X', 'country' => 'Y']);

        $type = RoomType::create(['name' => 'T', 'capacity' => 1, 'bed_count' => 1, 'bed_layout' => 'single', 'base_price' => 1000]);
        $room = Room::create(['room_number' => '1', 'room_type_id' => $type->id, 'floor' => 1, 'price_per_night' => 1000, 'status' => 'available']);

        $booking = Booking::create([
            'booking_reference' => 'BK-T-1', 'guest_id' => $guest2->id, 'room_id' => $room->id,
            'check_in_date' => today(), 'check_out_date' => today()->addDay(),
            'num_nights' => 1, 'num_guests' => 1,
            'rate_per_night' => 1000, 'subtotal' => 1000, 'tax_rate' => 13, 'tax_amount' => 130, 'total_amount' => 1130,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($user1)->get("/portal/bookings/{$booking->id}");
        $response->assertForbidden();
    }

    public function test_guest_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create(['role' => 'guest']);
        Guest::create(['user_id' => $user->id, 'first_name' => 'T', 'last_name' => 'U', 'phone' => '1', 'nationality' => 'X', 'country' => 'Y']);

        $response = $this->actingAs($user)->get('/admin/rooms');
        $response->assertForbidden();
    }

    public function test_guest_cannot_access_receptionist_routes(): void
    {
        $user = User::factory()->create(['role' => 'guest']);
        Guest::create(['user_id' => $user->id, 'first_name' => 'T', 'last_name' => 'U', 'phone' => '1', 'nationality' => 'X', 'country' => 'Y']);

        $response = $this->actingAs($user)->get('/guests');
        $response->assertForbidden();
    }
}
