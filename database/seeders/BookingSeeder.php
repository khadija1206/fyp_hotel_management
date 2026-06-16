<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(BookingService::class);
        $guests = Guest::all();

        if ($guests->isEmpty()) {
            return;
        }

        Room::query()->where('status', '!=', 'maintenance')->update(['status' => 'available']);

        $room = Room::where('room_number', '101')->first();
        if ($room) {
            $this->createPastBooking($service, $guests[0], $room, today()->subDays(5), today()->subDays(2));
        }

        $room = Room::where('room_number', '102')->first();
        if ($room) {
            $this->createCheckedInBooking($service, $guests[1], $room, today()->subDays(1), today()->addDays(2));
        }

        $room = Room::where('room_number', '104')->first();
        if ($room && isset($guests[2])) {
            $this->createCheckedInBooking($service, $guests[2], $room, today()->subDays(2), today()->addDay());
        }

        $room = Room::where('room_number', '202')->first();
        if ($room && isset($guests[3])) {
            $this->createCheckedInBooking($service, $guests[3], $room, today(), today()->addDays(3));
        }

        $room = Room::where('room_number', '207')->first();
        if ($room && isset($guests[4])) {
            $this->createCheckedInBooking($service, $guests[4], $room, today()->subDays(1), today());
        }

        $room = Room::where('room_number', '208')->first();
        if ($room && isset($guests[5])) {
            $this->createCheckedInBooking($service, $guests[5], $room, today()->subDays(3), today()->addDays(2));
        }

        $room = Room::where('room_number', '204')->first();
        if ($room && isset($guests[6])) {
            $this->createConfirmedBooking($service, $guests[6], $room, today()->addDays(1), today()->addDays(4));
        }

        $room = Room::where('room_number', '304')->first();
        if ($room && isset($guests[7])) {
            $this->createConfirmedBooking($service, $guests[7], $room, today()->addDays(2), today()->addDays(5));
        }

        $room = Room::where('room_number', '103')->first();
        if ($room) {
            $this->createConfirmedBooking($service, $guests[0], $room, today(), today()->addDays(2));
        }
    }

    private function createPastBooking(BookingService $service, Guest $guest, Room $room, Carbon $checkIn, Carbon $checkOut): void
    {
        $totals = $service->calculateTotals($room, $checkIn, $checkOut);
        Booking::create(array_merge($totals, [
            'booking_reference' => $service->generateReference(),
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'actual_check_in_at' => $checkIn->copy()->setTime(14, 30),
            'actual_check_out_at' => $checkOut->copy()->setTime(11, 0),
            'num_guests' => 2,
            'status' => 'checked_out',
            'payment_status' => 'paid',
            'created_by' => 1,
        ]));
    }

    private function createCheckedInBooking(BookingService $service, Guest $guest, Room $room, Carbon $checkIn, Carbon $checkOut): void
    {
        $totals = $service->calculateTotals($room, $checkIn, $checkOut);
        Booking::create(array_merge($totals, [
            'booking_reference' => $service->generateReference(),
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'actual_check_in_at' => $checkIn->copy()->setTime(15, 0),
            'num_guests' => rand(1, 2),
            'status' => 'checked_in',
            'payment_status' => 'unpaid',
            'created_by' => 1,
        ]));
        $room->update(['status' => 'occupied']);
    }

    private function createConfirmedBooking(BookingService $service, Guest $guest, Room $room, Carbon $checkIn, Carbon $checkOut): void
    {
        $totals = $service->calculateTotals($room, $checkIn, $checkOut);
        Booking::create(array_merge($totals, [
            'booking_reference' => $service->generateReference(),
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'num_guests' => rand(1, 2),
            'status' => 'confirmed',
            'payment_status' => 'unpaid',
            'created_by' => 1,
        ]));
        $room->update(['status' => 'reserved']);
    }
}
