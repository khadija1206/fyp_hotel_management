<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\ComplaintStoreRequest;
use App\Models\Booking;
use App\Models\Complaint;
use App\Services\ComplaintService;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function __construct(private ComplaintService $service) {}

    public function index(Request $request)
    {
        $guest = auth()->user()->guest;

        if (! $guest) {
            return redirect()->route('guest.dashboard');
        }

        $complaints = Complaint::with(['booking', 'room', 'assignedTo'])
            ->where('guest_id', $guest->id)
            ->latest()
            ->paginate(10);

        return view('guest.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $guest = auth()->user()->guest;

        if (! $guest) {
            return redirect()->route('guest.dashboard');
        }

        $bookings = $guest->bookings()
            ->with('room')
            ->whereIn('status', ['checked_in', 'checked_out', 'confirmed'])
            ->latest('check_in_date')
            ->take(10)
            ->get();

        return view('guest.complaints.create', compact('bookings'));
    }

    public function store(ComplaintStoreRequest $request)
    {
        $guest = auth()->user()->guest;

        if (! $guest) {
            return redirect()->route('guest.dashboard');
        }

        $data = $request->validated();
        $data['guest_id'] = $guest->id;

        if (! empty($data['booking_id'])) {
            $booking = Booking::find($data['booking_id']);
            if ($booking && $booking->guest_id === $guest->id) {
                $data['room_id'] = $booking->room_id;
            }
        }

        $complaint = $this->service->createComplaint($data);

        return redirect()->route('guest.complaints.show', $complaint)
            ->with('success', "Complaint submitted. Reference: {$complaint->complaint_reference}");
    }

    public function show(Complaint $complaint)
    {
        $guest = auth()->user()->guest;

        if (! $guest || $complaint->guest_id !== $guest->id) {
            abort(403);
        }

        $complaint->load(['booking', 'room', 'assignedTo', 'resolvedBy']);

        return view('guest.complaints.show', compact('complaint'));
    }

    public function reopen(Request $request, Complaint $complaint)
    {
        $guest = auth()->user()->guest;

        if (! $guest || $complaint->guest_id !== $guest->id) {
            abort(403);
        }

        if (! $complaint->isResolved()) {
            return back()->with('error', 'Only resolved complaints can be reopened.');
        }

        $request->validate(['reopen_reason' => 'required|string|max:500']);

        $this->service->reopen($complaint, $request->reopen_reason);

        return back()->with('success', 'Complaint reopened. Staff will review again.');
    }
}
