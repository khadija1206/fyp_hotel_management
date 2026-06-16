<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Receptionist\WalkInRequest;
use App\Models\Room;
use App\Services\BookingService;

class WalkInController extends Controller
{
    public function __construct(private BookingService $service) {}

    public function create()
    {
        $availableRooms = Room::with('roomType')
            ->where('status', 'available')
            ->where('is_active', true)
            ->orderBy('floor')
            ->orderBy('room_number')
            ->get();

        return view('receptionist.walk-in.create', compact('availableRooms'));
    }

    public function store(WalkInRequest $request)
    {
        $room = Room::findOrFail($request->room_id);

        if ($room->status !== 'available') {
            return back()->withInput()->with('error', 'Selected room is no longer available.');
        }

        $guestData = $request->only([
            'first_name', 'last_name', 'phone', 'email',
            'cnic', 'passport_number', 'nationality', 'address',
        ]);

        $bookingData = [
            'check_out_date' => $request->check_out_date,
            'num_guests' => $request->num_guests,
            'notes' => $request->notes,
        ];

        try {
            $booking = $this->service->createWalkIn($guestData, $bookingData, $room);

            return redirect()->route('bookings.show', $booking)
                ->with('success', "Walk-in completed. {$booking->guest->full_name} checked in to Room {$room->room_number}.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed: '.$e->getMessage());
        }
    }
}
