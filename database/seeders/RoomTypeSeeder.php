<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Single', 'description' => 'Cozy single occupancy room',
                'capacity' => 1, 'bed_count' => 1, 'bed_layout' => 'single',
                'base_price' => 4500, 'amenities' => 'WiFi, AC, TV, Hot Water',
            ],
            [
                'name' => 'Double', 'description' => 'Comfortable double bed room',
                'capacity' => 2, 'bed_count' => 1, 'bed_layout' => 'double',
                'base_price' => 6500, 'amenities' => 'WiFi, AC, TV, Hot Water, Mini Fridge',
            ],
            [
                'name' => 'Twin', 'description' => 'Two single beds for two guests',
                'capacity' => 2, 'bed_count' => 2, 'bed_layout' => 'twin',
                'base_price' => 7000, 'amenities' => 'WiFi, AC, TV, Hot Water',
            ],
            [
                'name' => 'Deluxe Suite', 'description' => 'Spacious suite with living area',
                'capacity' => 4, 'bed_count' => 2, 'bed_layout' => 'suite',
                'base_price' => 14000, 'amenities' => 'WiFi, AC, Smart TV, Mini Bar, Living Room, Bathtub, Room Service',
            ],
        ];

        foreach ($types as $type) {
            RoomType::updateOrCreate(['name' => $type['name']], array_merge($type, ['is_active' => true]));
        }
    }
}
