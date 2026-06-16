<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    public function __construct(private BookingService $service) {}

    public function index()
    {
        $bookings = Booking::with(['guest', 'room.roomType'])
            ->where('status', 'checked_in')
            ->orderBy('check_out_date')
            ->paginate(20);

        return view('receptionist.check-out.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if (! $booking->canBeCheckedOut()) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking cannot be checked out.');
        }

        $booking->load(['guest', 'room.roomType']);

        return view('receptionist.check-out.show', compact('booking'));
    }

    public function process(Request $request, Booking $booking)
    {
        try {
            $this->service->checkOut($booking);

            return redirect()->route('bookings.show', $booking)
                ->with('success', "Guest checked out. Room {$booking->room->room_number} is now available.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
