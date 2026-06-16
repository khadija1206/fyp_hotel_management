<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;

class CheckInController extends Controller
{
    public function __construct(private BookingService $service) {}

    public function index()
    {
        $bookings = Booking::with(['guest', 'room.roomType'])
            ->where('status', 'confirmed')
            ->whereDate('check_in_date', '<=', today())
            ->orderBy('check_in_date')
            ->paginate(20);

        return view('receptionist.check-in.index', compact('bookings'));
    }

    public function process(Booking $booking)
    {
        try {
            $this->service->checkIn($booking);

            return back()->with('success', "Guest {$booking->guest->full_name} checked in to Room {$booking->room->room_number}.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
