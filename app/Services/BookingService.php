<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function generateReference(): string
    {
        $year = now()->year;
        $lastBooking = Booking::where('booking_reference', 'like', "BK-{$year}-%")
            ->orderByDesc('id')
            ->first();

        $next = 1;
        if ($lastBooking) {
            $parts = explode('-', $lastBooking->booking_reference);
            $next = (int) end($parts) + 1;
        }

        return sprintf('BK-%d-%05d', $year, $next);
    }

    public function calculateTotals(Room $room, $checkIn, $checkOut): array
    {
        $checkIn = Carbon::parse($checkIn);
        $checkOut = Carbon::parse($checkOut);
        $nights = max(1, $checkIn->diffInDays($checkOut));

        $rate = (float) $room->price_per_night;
        $subtotal = $rate * $nights;
        $taxRate = (float) Setting::get('tax_rate', 13);
        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $total = $subtotal + $taxAmount;

        return [
            'num_nights' => $nights,
            'rate_per_night' => $rate,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $total,
        ];
    }

    public function getAvailableRooms($checkIn, $checkOut, ?int $roomTypeId = null)
    {
        $query = Room::with('roomType')
            ->where('is_active', true)
            ->where('status', '!=', 'maintenance');

        if ($roomTypeId) {
            $query->where('room_type_id', $roomTypeId);
        }

        $rooms = $query->get();

        return $rooms->filter(function ($room) use ($checkIn, $checkOut) {
            return $room->isAvailableForDates($checkIn, $checkOut);
        });
    }

    public function createBooking(array $data, Room $room): Booking
    {
        return DB::transaction(function () use ($data, $room) {
            $totals = $this->calculateTotals($room, $data['check_in_date'], $data['check_out_date']);

            $booking = Booking::create(array_merge($data, $totals, [
                'booking_reference' => $this->generateReference(),
                'room_id' => $room->id,
                'status' => 'confirmed',
                'payment_status' => 'unpaid',
                'is_walk_in' => $data['is_walk_in'] ?? false,
                'created_by' => auth()->id(),
            ]));

            $room->update(['status' => 'reserved']);

            AuditLogger::log(
                'booking.created',
                $booking,
                "Booking {$booking->booking_reference} created for guest #{$booking->guest_id}, room {$room->room_number}"
            );

            return $booking;
        });
    }

    public function checkIn(Booking $booking): Booking
    {
        if (! $booking->canBeCheckedIn() && ! $booking->is_walk_in) {
            throw new \Exception("Booking cannot be checked in. Current status: {$booking->status}");
        }

        return DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'checked_in',
                'actual_check_in_at' => now(),
            ]);

            $booking->room->update(['status' => 'occupied']);

            AuditLogger::log(
                'booking.checked_in',
                $booking,
                "Guest checked in: {$booking->booking_reference}"
            );

            return $booking->fresh();
        });
    }

    public function checkOut(Booking $booking): Booking
    {
        if (! $booking->canBeCheckedOut()) {
            throw new \Exception("Booking cannot be checked out. Current status: {$booking->status}");
        }

        return DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'checked_out',
                'actual_check_out_at' => now(),
            ]);

            $booking->room->update(['status' => 'available']);

            AuditLogger::log(
                'booking.checked_out',
                $booking,
                "Guest checked out: {$booking->booking_reference}"
            );

            return $booking->fresh();
        });
    }

    public function cancel(Booking $booking, ?string $reason = null): Booking
    {
        if (! $booking->canBeCancelled()) {
            throw new \Exception("Booking cannot be cancelled. Current status: {$booking->status}");
        }

        return DB::transaction(function () use ($booking, $reason) {
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            if ($booking->room->status === 'reserved') {
                $booking->room->update(['status' => 'available']);
            }

            AuditLogger::log(
                'booking.cancelled',
                $booking,
                'Booking cancelled: '.$booking->booking_reference.'. Reason: '.($reason ?? 'none')
            );

            return $booking->fresh();
        });
    }

    public function createWalkIn(array $guestData, array $bookingData, Room $room): Booking
    {
        return DB::transaction(function () use ($guestData, $bookingData, $room) {
            $guest = Guest::create(array_merge($guestData, [
                'created_by' => auth()->id(),
            ]));

            $bookingData['guest_id'] = $guest->id;
            $bookingData['check_in_date'] = today();
            $bookingData['is_walk_in'] = true;

            $booking = $this->createBooking($bookingData, $room);

            return $this->checkIn($booking);
        });
    }
}
