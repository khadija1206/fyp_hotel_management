<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Receptionist\BookingStoreRequest;
use App\Http\Requests\Receptionist\BookingUpdateRequest;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\AuditLogger;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private BookingService $service) {}

    public function index(Request $request)
    {
        $query = Booking::with(['guest', 'room.roomType']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('booking_reference', 'like', "%{$term}%")
                    ->orWhereHas('guest', fn ($g) => $g->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('check_in_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_in_date', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(20)->withQueryString();

        return view('receptionist.bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $roomTypes = RoomType::active()->orderBy('name')->get();
        $selectedGuest = $request->filled('guest_id') ? Guest::find($request->guest_id) : null;
        $guests = Guest::orderBy('first_name')->limit(50)->get();

        return view('receptionist.bookings.create', compact('roomTypes', 'guests', 'selectedGuest'));
    }

    public function availableRooms(Request $request)
    {
        $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $rooms = $this->service->getAvailableRooms(
            $request->check_in_date,
            $request->check_out_date
        );

        return response()->json([
            'rooms' => $rooms->map(fn ($r) => [
                'id' => $r->id,
                'room_number' => $r->room_number,
                'floor' => $r->floor,
                'type' => $r->roomType->name,
                'capacity' => $r->roomType->capacity,
                'price' => (float) $r->price_per_night,
                'price_formatted' => formatPKR($r->price_per_night),
            ])->values(),
        ]);
    }

    public function store(BookingStoreRequest $request)
    {
        $room = Room::findOrFail($request->room_id);

        if (! $room->isAvailableForDates($request->check_in_date, $request->check_out_date)) {
            return back()->withInput()->with('error', "Room {$room->room_number} is no longer available for those dates.");
        }

        $booking = $this->service->createBooking($request->validated(), $room);

        return redirect()->route('bookings.show', $booking)
            ->with('success', "Booking {$booking->booking_reference} created.");
    }

    public function show(Booking $booking)
    {
        $booking->load(['guest', 'room.roomType', 'createdBy']);

        return view('receptionist.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        if (! in_array($booking->status, ['confirmed', 'checked_in'])) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Only confirmed or checked-in bookings can be modified.');
        }

        return view('receptionist.bookings.edit', compact('booking'));
    }

    public function update(BookingUpdateRequest $request, Booking $booking)
    {
        $booking->update($request->validated());

        AuditLogger::log('booking.updated', $booking, "Updated booking: {$booking->booking_reference}");

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $request->validate(['cancellation_reason' => 'nullable|string|max:500']);

        try {
            $this->service->cancel($booking, $request->cancellation_reason);

            return back()->with('success', "Booking {$booking->booking_reference} cancelled.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Booking $booking)
    {
        return back()->with('error', 'Bookings cannot be deleted, only cancelled.');
    }
}
