<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $guest = auth()->user()->guest;

        if (! $guest) {
            $nameParts = explode(' ', trim(auth()->user()->name), 2);
            $guest = Guest::create([
                'user_id' => auth()->id(),
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'email' => auth()->user()->email,
                'phone' => '',
                'nationality' => 'Pakistani',
                'country' => 'Pakistan',
                'created_by' => auth()->id(),
            ]);
        }

        return view('guest.profile.edit', compact('guest'));
    }

    public function update(Request $request)
    {
        $guest = auth()->user()->guest;

        if (! $guest) {
            return redirect()->route('guest.dashboard');
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'phone' => 'required|string|max:30',
            'cnic' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:30',
            'nationality' => 'required|string|max:60',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:80',
            'country' => 'required|string|max:80',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact_name' => 'nullable|string|max:120',
            'emergency_contact_phone' => 'nullable|string|max:30',
        ]);

        $guest->update($data);

        auth()->user()->update([
            'name' => $data['first_name'].' '.$data['last_name'],
        ]);

        AuditLogger::log('guest.profile_updated', $guest, 'Guest updated own profile');

        return back()->with('success', 'Profile updated successfully.');
    }
}
