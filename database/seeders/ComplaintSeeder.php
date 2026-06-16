<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\User;
use App\Services\ComplaintService;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(ComplaintService::class);
        $admin = User::where('role', 'admin')->first();
        $receptionist = User::where('role', 'receptionist')->first();
        auth()->setUser($admin);

        $booking = Booking::where('status', 'checked_in')->first();
        if ($booking) {
            $service->createComplaint([
                'guest_id' => $booking->guest_id,
                'booking_id' => $booking->id,
                'room_id' => $booking->room_id,
                'title' => 'AC is broken',
                'description' => 'The air conditioner in my room stopped working last night. It is very hot and I cannot sleep. Please send someone urgently.',
                'category' => 'room',
            ]);
        }

        $booking2 = Booking::where('status', 'checked_in')->skip(1)->first();
        if ($booking2) {
            $complaint = $service->createComplaint([
                'guest_id' => $booking2->guest_id,
                'booking_id' => $booking2->id,
                'room_id' => $booking2->room_id,
                'title' => 'Slow WiFi connection',
                'description' => 'The internet is very slow in my room. I cannot work properly. Can it be fixed?',
                'category' => 'service',
            ]);
            $service->assign($complaint, $receptionist->id);
            $service->startWork($complaint);
        }

        $pastBooking = Booking::where('status', 'checked_out')->first();
        if ($pastBooking) {
            $complaint = $service->createComplaint([
                'guest_id' => $pastBooking->guest_id,
                'booking_id' => $pastBooking->id,
                'room_id' => $pastBooking->room_id,
                'title' => 'Bathroom smell',
                'description' => 'There is a bad smell coming from the bathroom drain.',
                'category' => 'cleanliness',
            ]);
            $service->assign($complaint, $receptionist->id);
            $service->startWork($complaint);
            auth()->setUser($receptionist);
            $service->resolve($complaint, 'Plumber inspected the drain and cleared a blockage. Sanitized and deodorized the bathroom. Verified with guest before checkout — issue resolved.');
            auth()->setUser($admin);
        }

        $guest = Guest::whereNotNull('user_id')->first();
        if ($guest) {
            $service->createComplaint([
                'guest_id' => $guest->id,
                'title' => 'Pillow request',
                'description' => 'Could I get an extra pillow for my room? Thank you.',
                'category' => 'service',
            ]);

            $service->createComplaint([
                'guest_id' => $guest->id,
                'title' => 'Water leak in bathroom',
                'description' => 'There is a urgent water leak from the bathroom sink. Floor is getting wet. Please send someone immediately.',
                'category' => 'room',
            ]);
        }
    }
}
