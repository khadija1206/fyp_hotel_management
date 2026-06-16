<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Guest;
use App\Models\User;
use App\Services\ComplaintService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintTest extends TestCase
{
    use RefreshDatabase;

    public function test_auto_priority_detects_high_priority_keyword(): void
    {
        $service = app(ComplaintService::class);
        $this->assertEquals('high', $service->detectPriority('AC broken', 'My AC is broken and it is hot'));
        $this->assertEquals('high', $service->detectPriority('Urgent', 'Water leak in bathroom'));
        $this->assertEquals('medium', $service->detectPriority('Issue', 'The TV is not working properly'));
        $this->assertEquals('low', $service->detectPriority('Question', 'When is breakfast served?'));
    }

    public function test_guest_can_submit_complaint(): void
    {
        $user = User::factory()->create(['role' => 'guest']);
        $guest = Guest::create(['user_id' => $user->id, 'first_name' => 'T', 'last_name' => 'G', 'phone' => '1', 'nationality' => 'X', 'country' => 'Y']);

        $this->actingAs($user)->post('/portal/complaints', [
            'title' => 'Test complaint',
            'description' => 'This is a test description for the complaint with enough length.',
            'category' => 'service',
        ]);

        $this->assertDatabaseHas('complaints', ['guest_id' => $guest->id, 'title' => 'Test complaint']);
    }

    public function test_guest_cannot_see_other_complaints(): void
    {
        $user1 = User::factory()->create(['role' => 'guest']);
        $user2 = User::factory()->create(['role' => 'guest']);
        Guest::create(['user_id' => $user1->id, 'first_name' => 'A', 'last_name' => 'A', 'phone' => '1', 'nationality' => 'X', 'country' => 'Y']);
        $guest2 = Guest::create(['user_id' => $user2->id, 'first_name' => 'B', 'last_name' => 'B', 'phone' => '2', 'nationality' => 'X', 'country' => 'Y']);

        $complaint = Complaint::create([
            'complaint_reference' => 'CMP-T-1', 'guest_id' => $guest2->id,
            'title' => 'Test', 'description' => 'Desc', 'category' => 'service',
            'priority' => 'low', 'status' => 'pending',
        ]);

        $response = $this->actingAs($user1)->get("/portal/complaints/{$complaint->id}");
        $response->assertForbidden();
    }

    public function test_admin_can_assign_complaint(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $receptionist = User::factory()->create(['role' => 'receptionist']);
        $guestUser = User::factory()->create(['role' => 'guest']);
        $guest = Guest::create(['user_id' => $guestUser->id, 'first_name' => 'A', 'last_name' => 'A', 'phone' => '1', 'nationality' => 'X', 'country' => 'Y']);

        $complaint = Complaint::create([
            'complaint_reference' => 'CMP-T-1', 'guest_id' => $guest->id,
            'title' => 'T', 'description' => 'D', 'category' => 'service',
            'priority' => 'low', 'status' => 'pending',
        ]);

        $this->actingAs($admin)->put("/complaints/{$complaint->id}/assign", [
            'assigned_to' => $receptionist->id,
            'priority' => 'high',
        ]);

        $fresh = $complaint->fresh();
        $this->assertEquals($receptionist->id, $fresh->assigned_to);
        $this->assertEquals('high', $fresh->priority);
    }

    public function test_resolve_requires_notes_and_records_resolver(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guestUser = User::factory()->create(['role' => 'guest']);
        $guest = Guest::create(['user_id' => $guestUser->id, 'first_name' => 'A', 'last_name' => 'A', 'phone' => '1', 'nationality' => 'X', 'country' => 'Y']);

        $complaint = Complaint::create([
            'complaint_reference' => 'CMP-T-1', 'guest_id' => $guest->id,
            'title' => 'T', 'description' => 'D', 'category' => 'service',
            'priority' => 'low', 'status' => 'in_progress',
        ]);

        $this->actingAs($admin)->put("/complaints/{$complaint->id}/resolve", [
            'resolution_notes' => 'Fixed the issue by replacing the faulty component.',
        ]);

        $fresh = $complaint->fresh();
        $this->assertEquals('resolved', $fresh->status);
        $this->assertEquals($admin->id, $fresh->resolved_by);
        $this->assertNotNull($fresh->resolved_at);
    }

    public function test_guest_can_reopen_resolved_complaint(): void
    {
        $guestUser = User::factory()->create(['role' => 'guest']);
        $guest = Guest::create(['user_id' => $guestUser->id, 'first_name' => 'A', 'last_name' => 'A', 'phone' => '1', 'nationality' => 'X', 'country' => 'Y']);

        $complaint = Complaint::create([
            'complaint_reference' => 'CMP-T-1', 'guest_id' => $guest->id,
            'title' => 'T', 'description' => 'D', 'category' => 'service',
            'priority' => 'low', 'status' => 'resolved',
            'resolution_notes' => 'Done.', 'resolved_at' => now(),
        ]);

        $this->actingAs($guestUser)->put("/portal/complaints/{$complaint->id}/reopen", [
            'reopen_reason' => 'Still not working properly.',
        ]);

        $this->assertEquals('reopened', $complaint->fresh()->status);
    }
}
