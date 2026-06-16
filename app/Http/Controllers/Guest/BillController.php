<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;

class BillController extends Controller
{
    public function index()
    {
        $guest = auth()->user()->guest;

        if (! $guest) {
            return redirect()->route('guest.dashboard');
        }

        $bookings = Booking::with('room.roomType')
            ->where('guest_id', $guest->id)
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->latest('check_in_date')
            ->paginate(15);

        return view('guest.bills.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $guest = auth()->user()->guest;

        if (! $guest || $booking->guest_id !== $guest->id) {
            abort(403);
        }

        $booking->load(['room.roomType', 'guest']);

        $hotel = [
            'name' => Setting::get('hotel_name'),
            'address' => Setting::get('hotel_address'),
            'phone' => Setting::get('hotel_phone'),
            'email' => Setting::get('hotel_email'),
        ];

        return view('guest.bills.show', compact('booking', 'hotel'));
    }
}
