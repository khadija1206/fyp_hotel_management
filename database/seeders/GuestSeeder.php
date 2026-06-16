<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        $guests = [
            ['Ahmed', 'Khan', 'ahmed.khan@example.com', '+92-300-1234567', '35202-1234567-1', 'Pakistani', 'Gulberg, Lahore', true],
            ['Fatima', 'Noor', 'fatima.noor@example.com', '+92-321-2345678', '35202-2345678-2', 'Pakistani', 'DHA Phase 5, Lahore', true],
            ['Sarah', 'Malik', 'sarah.m@example.com', '+92-333-3456789', '42101-3456789-3', 'Pakistani', 'Clifton, Karachi', true],
            ['Imran', 'Sheikh', 'imran.sheikh@example.com', '+92-301-4567890', '35202-4567890-4', 'Pakistani', 'Model Town, Lahore', false],
            ['Aisha', 'Rahman', 'aisha.r@example.com', '+92-322-5678901', '35202-5678901-5', 'Pakistani', 'Bahria Town, Islamabad', false],
            ['John', 'Smith', 'john.smith@example.com', '+1-555-1234567', null, 'American', '123 Main St, New York', false],
            ['Hassan', 'Ali', 'hassan.ali@example.com', '+92-310-6789012', '35202-6789012-6', 'Pakistani', 'Johar Town, Lahore', false],
            ['Zainab', 'Tariq', 'zainab.t@example.com', '+92-345-7890123', '35202-7890123-7', 'Pakistani', 'PECHS, Karachi', false],
        ];

        foreach ($guests as [$first, $last, $email, $phone, $cnic, $nat, $addr, $hasLogin]) {
            $userId = null;
            if ($hasLogin) {
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $first.' '.$last,
                        'password' => Hash::make('password'),
                        'role' => 'guest',
                        'is_active' => true,
                        'email_verified_at' => now(),
                    ]
                );
                $userId = $user->id;
            }

            Guest::updateOrCreate(
                ['email' => $email],
                [
                    'user_id' => $userId,
                    'first_name' => $first, 'last_name' => $last,
                    'phone' => $phone, 'cnic' => $cnic,
                    'nationality' => $nat, 'address' => $addr,
                    'country' => $nat === 'Pakistani' ? 'Pakistan' : 'USA',
                    'passport_number' => $nat !== 'Pakistani' ? 'P'.rand(1000000, 9999999) : null,
                    'created_by' => 1,
                ]
            );
        }
    }
}
