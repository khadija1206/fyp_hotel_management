<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class FloorPlanController extends Controller
{
    public function index(Request $request)
    {
        $floors = Room::distinct()->orderBy('floor')->pluck('floor');

        if ($floors->isEmpty()) {
            return view('floorplan.empty');
        }

        $currentFloor = $request->floor ?? $floors->first();

        $rooms = $this->roomsForFloor($currentFloor);
        $stats = $this->floorStats($currentFloor);

        return view('floorplan.index', compact('floors', 'currentFloor', 'rooms', 'stats'));
    }

    public function floor(int $floor)
    {
        return response()->json([
            'rooms' => $this->roomsForFloor($floor)->map(fn ($r) => $this->roomToArray($r)),
            'stats' => $this->floorStats($floor),
        ]);
    }

    public function roomDetail(Room $room)
    {
        $room->load('roomType');

        $activeBooking = Booking::with('guest')
            ->where('room_id', $room->id)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->latest()
            ->first();

        return response()->json([
            'room' => [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'floor' => $room->floor,
                'type' => $room->roomType->name,
                'capacity' => $room->roomType->capacity,
                'bed_layout' => $room->roomType->bed_layout,
                'bed_count' => $room->roomType->bed_count,
                'price' => formatPKR($room->price_per_night),
                'status' => $room->status,
                'status_label' => $room->status_label,
                'status_color' => $room->status_color,
                'notes' => $room->notes,
                'amenities' => $room->roomType->amenities_array,
            ],
            'booking' => $activeBooking ? [
                'id' => $activeBooking->id,
                'reference' => $activeBooking->booking_reference,
                'check_in_date' => formatDate($activeBooking->check_in_date),
                'check_out_date' => formatDate($activeBooking->check_out_date),
                'total' => formatPKR($activeBooking->total_amount),
                'status' => $activeBooking->status,
                'guest' => [
                    'id' => $activeBooking->guest->id,
                    'name' => $activeBooking->guest->full_name,
                    'phone' => $activeBooking->guest->phone,
                ],
            ] : null,
            'links' => $this->buildActionLinks($room, $activeBooking),
        ]);
    }

    public function statusPoll(int $floor)
    {
        return response()->json([
            'rooms' => Room::where('floor', $floor)
                ->select('id', 'status')
                ->get()
                ->map(fn ($r) => [
                    'id' => $r->id,
                    'status' => $r->status,
                    'status_label' => ucfirst($r->status),
                    'color_class' => $this->statusToColorClass($r->status),
                ]),
            'stats' => $this->floorStats($floor),
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    private function roomsForFloor(int $floor)
    {
        return Room::with('roomType')
            ->where('floor', $floor)
            ->where('is_active', true)
            ->orderBy('room_number')
            ->get();
    }

    private function floorStats(int $floor): array
    {
        $rooms = Room::where('floor', $floor)->where('is_active', true);

        return [
            'available' => (clone $rooms)->where('status', 'available')->count(),
            'occupied' => (clone $rooms)->where('status', 'occupied')->count(),
            'reserved' => (clone $rooms)->where('status', 'reserved')->count(),
            'maintenance' => (clone $rooms)->where('status', 'maintenance')->count(),
            'total' => (clone $rooms)->count(),
        ];
    }

    private function roomToArray(Room $room): array
    {
        return [
            'id' => $room->id,
            'room_number' => $room->room_number,
            'floor' => $room->floor,
            'type' => $room->roomType->name,
            'bed_layout' => $room->roomType->bed_layout,
            'status' => $room->status,
            'status_label' => $room->status_label,
            'position_x' => $room->position_x,
            'position_y' => $room->position_y,
            'width' => $room->width,
            'height' => $room->height,
        ];
    }

    private function statusToColorClass(string $status): string
    {
        return match ($status) {
            'available' => 'fp-available',
            'occupied' => 'fp-occupied',
            'reserved' => 'fp-reserved',
            'maintenance' => 'fp-maintenance',
            default => 'fp-available',
        };
    }

    private function buildActionLinks(Room $room, ?Booking $booking): array
    {
        $links = [];

        if ($room->status === 'available') {
            $links[] = ['label' => 'New Booking', 'icon' => 'calendar-plus', 'url' => route('bookings.create'), 'variant' => 'primary'];
            $links[] = ['label' => 'Walk-In', 'icon' => 'person-walking', 'url' => route('walk-in.create'), 'variant' => 'secondary'];
        }

        if ($room->status === 'occupied' && $booking) {
            $links[] = ['label' => 'Booking Details', 'icon' => 'eye', 'url' => route('bookings.show', $booking->id), 'variant' => 'primary'];
            $links[] = ['label' => 'Check-Out', 'icon' => 'box-arrow-right', 'url' => route('check-out.show', $booking->id), 'variant' => 'success'];

            if ($booking->amount_due > 0) {
                $links[] = ['label' => 'Record Payment', 'icon' => 'cash-coin', 'url' => route('payments.create', $booking->id), 'variant' => 'secondary'];
            }
        }

        if ($room->status === 'reserved' && $booking) {
            $links[] = ['label' => 'Booking Details', 'icon' => 'eye', 'url' => route('bookings.show', $booking->id), 'variant' => 'primary'];
        }

        if (auth()->user()->isAdmin()) {
            $links[] = ['label' => 'Edit Room', 'icon' => 'pencil', 'url' => route('admin.rooms.edit', $room), 'variant' => 'secondary'];
        }

        return $links;
    }
}
