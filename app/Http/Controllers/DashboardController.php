<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isReceptionist()) {
            return $this->receptionistDashboard();
        }

        return redirect()->route('guest.dashboard');
    }

    private function adminDashboard()
    {
        $totalRooms = Room::active()->count();
        $availableRooms = Room::active()->where('status', 'available')->count();
        $occupiedRooms = Room::active()->where('status', 'occupied')->count();
        $maintenanceRooms = Room::active()->where('status', 'maintenance')->count();
        $reservedRooms = Room::active()->where('status', 'reserved')->count();

        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

        $totalStaff = User::whereIn('role', ['admin', 'receptionist'])->where('is_active', true)->count();
        $totalGuests = User::where('role', 'guest')->count();

        $roomsByStatus = [
            'available' => $availableRooms,
            'occupied' => $occupiedRooms,
            'reserved' => $reservedRooms,
            'maintenance' => $maintenanceRooms,
        ];

        $roomsByFloor = Room::active()
            ->selectRaw("floor, COUNT(*) as total, SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) as occupied")
            ->groupBy('floor')
            ->orderBy('floor')
            ->get();

        $recentActivity = AuditLog::with('user')->latest()->take(8)->get();

        return view('dashboard.admin', compact(
            'totalRooms', 'availableRooms', 'occupiedRooms', 'maintenanceRooms',
            'reservedRooms', 'occupancyRate', 'totalStaff', 'totalGuests',
            'roomsByStatus', 'roomsByFloor', 'recentActivity'
        ));
    }

    private function receptionistDashboard()
    {
        $today = today();

        $stats = [
            'available_rooms' => Room::active()->where('status', 'available')->count(),
            'occupied_rooms' => Room::active()->where('status', 'occupied')->count(),
            'todays_check_ins' => Booking::where('status', 'confirmed')
                ->whereDate('check_in_date', '<=', $today)
                ->count(),
            'todays_check_outs' => Booking::where('status', 'checked_in')
                ->whereDate('check_out_date', $today)
                ->count(),
            'walk_ins_today' => Booking::where('is_walk_in', true)
                ->whereDate('created_at', $today)
                ->count(),
        ];

        $pendingCheckIns = Booking::with(['guest', 'room'])
            ->where('status', 'confirmed')
            ->whereDate('check_in_date', '<=', $today)
            ->orderBy('check_in_date')
            ->take(5)->get();

        $pendingCheckOuts = Booking::with(['guest', 'room'])
            ->where('status', 'checked_in')
            ->whereDate('check_out_date', $today)
            ->take(5)->get();

        return view('dashboard.receptionist', compact('stats', 'pendingCheckIns', 'pendingCheckOuts'));
    }
}
