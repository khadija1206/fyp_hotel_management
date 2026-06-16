<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FloorPlanTest extends TestCase
{
    use RefreshDatabase;

    private function setupRoom(string $status = 'available'): Room
    {
        $type = RoomType::create([
            'name' => 'T', 'capacity' => 2, 'bed_count' => 1, 'bed_layout' => 'double', 'base_price' => 5000,
        ]);

        return Room::create([
            'room_number' => '101', 'room_type_id' => $type->id, 'floor' => 1,
            'price_per_night' => 5000, 'status' => $status,
        ]);
    }

    public function test_admin_can_view_floor_plan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->setupRoom();

        $response = $this->actingAs($admin)->get('/floor-plan');
        $response->assertOk();
        $response->assertSee('Floor Plan');
    }

    public function test_receptionist_can_view_floor_plan(): void
    {
        $user = User::factory()->create(['role' => 'receptionist']);
        $this->setupRoom();

        $response = $this->actingAs($user)->get('/floor-plan');
        $response->assertOk();
    }

    public function test_guest_cannot_view_floor_plan(): void
    {
        $user = User::factory()->create(['role' => 'guest']);

        $response = $this->actingAs($user)->get('/floor-plan');
        $response->assertForbidden();
    }

    public function test_polling_endpoint_returns_lightweight_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->setupRoom('occupied');

        $response = $this->actingAs($admin)->get('/floor-plan/poll/1');
        $response->assertOk();
        $response->assertJsonStructure(['rooms' => [['id', 'status', 'status_label', 'color_class']], 'stats', 'updated_at']);
    }

    public function test_room_detail_endpoint(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $room = $this->setupRoom();

        $response = $this->actingAs($admin)->get("/floor-plan/rooms/{$room->id}/detail");
        $response->assertOk();
        $response->assertJsonStructure(['room' => ['id', 'room_number', 'status'], 'links']);
    }

    public function test_admin_can_access_layout_editor(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->setupRoom();

        $response = $this->actingAs($admin)->get('/admin/layout-editor');
        $response->assertOk();
        $response->assertSee('Layout Editor');
    }

    public function test_receptionist_cannot_access_layout_editor(): void
    {
        $user = User::factory()->create(['role' => 'receptionist']);

        $response = $this->actingAs($user)->get('/admin/layout-editor');
        $response->assertForbidden();
    }

    public function test_layout_save_updates_room_positions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $room = $this->setupRoom();

        $response = $this->actingAs($admin)->post('/admin/layout-editor/save', [
            'positions' => [
                ['id' => $room->id, 'x' => 5, 'y' => 3, 'w' => 4, 'h' => 3],
            ],
        ]);

        $response->assertOk();
        $fresh = $room->fresh();
        $this->assertEquals(5, $fresh->position_x);
        $this->assertEquals(3, $fresh->position_y);
        $this->assertEquals(4, $fresh->width);
        $this->assertEquals(3, $fresh->height);
    }
}
