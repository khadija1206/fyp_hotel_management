<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        $receptionists = [
            ['name' => 'Aisha Receptionist', 'email' => 'reception1@hotel.com'],
            ['name' => 'Bilal Receptionist', 'email' => 'reception2@hotel.com'],
        ];

        foreach ($receptionists as $r) {
            User::updateOrCreate(
                ['email' => $r['email']],
                [
                    'name' => $r['name'],
                    'password' => Hash::make('password'),
                    'role' => 'receptionist',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
