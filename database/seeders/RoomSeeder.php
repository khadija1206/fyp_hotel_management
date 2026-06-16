<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $single = RoomType::where('name', 'Single')->first();
        $double = RoomType::where('name', 'Double')->first();
        $twin = RoomType::where('name', 'Twin')->first();
        $suite = RoomType::where('name', 'Deluxe Suite')->first();

        $this->createRoom('101', $single, 1, 4500, 'available');
        $this->createRoom('102', $single, 1, 4500, 'occupied');
        $this->createRoom('103', $double, 1, 6500, 'available');
        $this->createRoom('104', $double, 1, 6500, 'occupied');
        $this->createRoom('105', $twin, 1, 7000, 'available');
        $this->createRoom('106', $twin, 1, 7000, 'maintenance', 'AC repair scheduled');

        $this->createRoom('201', $single, 2, 4800, 'available');
        $this->createRoom('202', $double, 2, 6800, 'occupied');
        $this->createRoom('203', $single, 2, 4800, 'available');
        $this->createRoom('204', $suite, 2, 14000, 'reserved');
        $this->createRoom('205', $double, 2, 6800, 'maintenance', 'Plumbing issue');
        $this->createRoom('206', $single, 2, 4800, 'available');
        $this->createRoom('207', $double, 2, 6800, 'occupied');
        $this->createRoom('208', $suite, 2, 14000, 'occupied');

        $this->createRoom('301', $twin, 3, 7200, 'available');
        $this->createRoom('302', $double, 3, 7000, 'available');
        $this->createRoom('303', $suite, 3, 15000, 'available');
        $this->createRoom('304', $double, 3, 7000, 'reserved');
        $this->createRoom('305', $twin, 3, 7200, 'available');
        $this->createRoom('306', $suite, 3, 15000, 'available');
    }

    private function createRoom(string $number, RoomType $type, int $floor, float $price, string $status, ?string $notes = null): void
    {
        Room::updateOrCreate(
            ['room_number' => $number],
            [
                'room_type_id' => $type->id,
                'floor' => $floor,
                'price_per_night' => $price,
                'status' => $status,
                'notes' => $notes,
                'is_active' => true,
                'position_x' => 0, 'position_y' => 0, 'width' => 2, 'height' => 2,
            ]
        );
    }
}
