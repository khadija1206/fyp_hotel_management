<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $guest = auth()->user()->guest;

        if (! $guest) {
            return redirect()->route('guest.dashboard');
        }

        $query = Booking::with('room.roomType')
            ->where('guest_id', $guest->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest('check_in_date')->paginate(10)->withQueryString();

        return view('guest.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $guest = auth()->user()->guest;

        if (! $guest || $booking->guest_id !== $guest->id) {
            abort(403);
        }

        $booking->load('room.roomType');

        return view('guest.bookings.show', compact('booking'));
    }
}
