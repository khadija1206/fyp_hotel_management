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

        $this->createRoom('101', $single, 1, 4500, 'available', null, 0, 0, 3, 2);
        $this->createRoom('102', $single, 1, 4500, 'occupied', null, 3, 0, 3, 2);
        $this->createRoom('103', $double, 1, 6500, 'available', null, 6, 0, 3, 2);
        $this->createRoom('104', $double, 1, 6500, 'occupied', null, 9, 0, 3, 2);
        $this->createRoom('105', $twin, 1, 7000, 'available', null, 0, 2, 4, 2);
        $this->createRoom('106', $twin, 1, 7000, 'maintenance', 'AC repair scheduled', 4, 2, 4, 2);

        $this->createRoom('201', $single, 2, 4800, 'available', null, 0, 0, 3, 2);
        $this->createRoom('202', $double, 2, 6800, 'occupied', null, 3, 0, 3, 2);
        $this->createRoom('203', $single, 2, 4800, 'available', null, 6, 0, 3, 2);
        $this->createRoom('204', $suite, 2, 14000, 'reserved', null, 9, 0, 3, 2);
        $this->createRoom('205', $double, 2, 6800, 'maintenance', 'Plumbing issue', 0, 2, 3, 2);
        $this->createRoom('206', $single, 2, 4800, 'available', null, 3, 2, 3, 2);
        $this->createRoom('207', $double, 2, 6800, 'occupied', null, 6, 2, 3, 2);
        $this->createRoom('208', $suite, 2, 14000, 'occupied', null, 9, 2, 3, 2);

        $this->createRoom('301', $twin, 3, 7200, 'available', null, 0, 0, 4, 2);
        $this->createRoom('302', $double, 3, 7000, 'available', null, 4, 0, 4, 2);
        $this->createRoom('303', $suite, 3, 15000, 'available', null, 8, 0, 4, 2);
        $this->createRoom('304', $double, 3, 7000, 'reserved', null, 0, 2, 4, 2);
        $this->createRoom('305', $twin, 3, 7200, 'available', null, 4, 2, 4, 2);
        $this->createRoom('306', $suite, 3, 15000, 'available', null, 8, 2, 4, 2);
    }

    private function createRoom(
        string $number, RoomType $type, int $floor, float $price, string $status,
        ?string $notes = null, int $x = 0, int $y = 0, int $w = 3, int $h = 2
    ): void {
        Room::updateOrCreate(
            ['room_number' => $number],
            [
                'room_type_id' => $type->id,
                'floor' => $floor,
                'price_per_night' => $price,
                'status' => $status,
                'notes' => $notes,
                'is_active' => true,
                'position_x' => $x,
                'position_y' => $y,
                'width' => $w,
                'height' => $h,
            ]
        );
    }
}
