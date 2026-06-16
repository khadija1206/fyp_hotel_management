<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ComplaintAssignRequest;
use App\Http\Requests\Receptionist\ComplaintResolveRequest;
use App\Models\Complaint;
use App\Models\User;
use App\Services\ComplaintService;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function __construct(private ComplaintService $service) {}

    public function index(Request $request)
    {
        $query = Complaint::with(['guest', 'room', 'assignedTo']);

        if ($request->filled('status')) {
            if ($request->status === 'open') {
                $query->open();
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('assigned')) {
            if ($request->assigned === 'unassigned') {
                $query->unassigned();
            } elseif ($request->assigned === 'me') {
                $query->where('assigned_to', auth()->id());
            }
        }

        if (auth()->user()->isReceptionist()) {
            $query->where(function ($q) {
                $q->where('assigned_to', auth()->id())
                    ->orWhereNull('assigned_to');
            });
        }

        $complaints = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'high_priority_open' => Complaint::open()->where('priority', 'high')->count(),
            'unassigned' => Complaint::unassigned()->open()->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'stats'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['guest', 'booking', 'room', 'assignedTo', 'resolvedBy', 'submittedBy']);

        $staff = User::whereIn('role', ['admin', 'receptionist'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.complaints.show', compact('complaint', 'staff'));
    }

    public function assign(ComplaintAssignRequest $request, Complaint $complaint)
    {
        $this->service->assign($complaint, $request->assigned_to);

        if ($request->filled('priority')) {
            $complaint->update(['priority' => $request->priority]);
        }

        return back()->with('success', 'Complaint assigned successfully.');
    }

    public function startWork(Complaint $complaint)
    {
        if (auth()->user()->isReceptionist() &&
            $complaint->assigned_to !== auth()->id() &&
            $complaint->assigned_to !== null) {
            return back()->with('error', 'You can only work on complaints assigned to you.');
        }

        $this->service->startWork($complaint);

        return back()->with('success', 'Work started on complaint.');
    }

    public function resolve(ComplaintResolveRequest $request, Complaint $complaint)
    {
        if (auth()->user()->isReceptionist() &&
            $complaint->assigned_to !== auth()->id() &&
            $complaint->assigned_to !== null) {
            return back()->with('error', 'You can only resolve complaints assigned to you.');
        }

        $this->service->resolve($complaint, $request->resolution_notes);

        return back()->with('success', 'Complaint marked as resolved.');
    }
}
