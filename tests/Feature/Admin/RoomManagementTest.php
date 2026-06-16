<?php

namespace Tests\Feature\Admin;

use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_rooms_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/rooms');

        $response->assertOk();
    }

    public function test_admin_can_create_a_room(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $type = RoomType::create([
            'name' => 'Test Type', 'capacity' => 2, 'bed_count' => 1,
            'bed_layout' => 'double', 'base_price' => 5000,
        ]);

        $response = $this->actingAs($admin)->post('/admin/rooms', [
            'room_number' => '999',
            'room_type_id' => $type->id,
            'floor' => 1,
            'price_per_night' => 5000,
            'status' => 'available',
        ]);

        $response->assertRedirect('/admin/rooms');
        $this->assertDatabaseHas('rooms', ['room_number' => '999']);
    }

    public function test_receptionist_cannot_access_admin_rooms(): void
    {
        $user = User::factory()->create(['role' => 'receptionist']);
        $response = $this->actingAs($user)->get('/admin/rooms');
        $response->assertForbidden();
    }
}
