<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $guest = auth()->user()->guest;

        if (! $guest) {
            return view('guest.no-profile');
        }

        $currentBooking = Booking::with('room.roomType')
            ->where('guest_id', $guest->id)
            ->where('status', 'checked_in')
            ->latest()
            ->first();

        $upcomingBookings = Booking::with('room.roomType')
            ->where('guest_id', $guest->id)
            ->where('status', 'confirmed')
            ->where('check_in_date', '>=', today())
            ->orderBy('check_in_date')
            ->take(3)
            ->get();

        $stats = [
            'total_stays' => Booking::where('guest_id', $guest->id)->where('status', 'checked_out')->count(),
            'active_bookings' => Booking::where('guest_id', $guest->id)->whereIn('status', ['confirmed', 'checked_in'])->count(),
            'total_spent' => Booking::where('guest_id', $guest->id)
                ->whereIn('status', ['checked_out', 'checked_in'])
                ->sum('total_amount'),
        ];

        return view('guest.dashboard', compact('guest', 'currentBooking', 'upcomingBookings', 'stats'));
    }
}
