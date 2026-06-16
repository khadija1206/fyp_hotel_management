<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;

class ReportsController extends Controller
{
    public function index()
    {
        $roomsByType = Room::with('roomType')
            ->get()
            ->groupBy('roomType.name')
            ->map(fn ($g) => $g->count());

        return view('admin.reports.index', compact('roomsByType'));
    }
}
