<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Receptionist\GuestStoreRequest;
use App\Http\Requests\Receptionist\GuestUpdateRequest;
use App\Models\Guest;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $guests = Guest::search($request->search)
            ->withCount('bookings')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('receptionist.guests.index', compact('guests'));
    }

    public function create()
    {
        return view('receptionist.guests.create');
    }

    public function store(GuestStoreRequest $request)
    {
        $data = $request->validated();

        if (empty($data['cnic']) && empty($data['passport_number'])) {
            return back()->withInput()->with('error', 'Either CNIC or Passport Number is required.');
        }

        $data['created_by'] = auth()->id();
        $guest = Guest::create($data);

        AuditLogger::log('guest.created', $guest, "Registered guest: {$guest->full_name}");

        return redirect()->route('guests.show', $guest)
            ->with('success', "Guest '{$guest->full_name}' registered.");
    }

    public function show(Guest $guest)
    {
        $guest->load(['bookings' => fn ($q) => $q->with('room.roomType')->latest()]);

        return view('receptionist.guests.show', compact('guest'));
    }

    public function edit(Guest $guest)
    {
        return view('receptionist.guests.edit', compact('guest'));
    }

    public function update(GuestUpdateRequest $request, Guest $guest)
    {
        $guest->update($request->validated());

        AuditLogger::log('guest.updated', $guest, "Updated guest: {$guest->full_name}");

        return redirect()->route('guests.show', $guest)
            ->with('success', 'Guest information updated.');
    }

    public function destroy(Guest $guest)
    {
        if ($guest->bookings()->whereIn('status', ['confirmed', 'checked_in'])->exists()) {
            return back()->with('error', 'Cannot delete guest with active bookings.');
        }

        $name = $guest->full_name;
        AuditLogger::log('guest.deleted', $guest, "Deleted guest: {$name}");
        $guest->delete();

        return redirect()->route('guests.index')->with('success', "Guest '{$name}' deleted.");
    }
}
