<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class LayoutEditorController extends Controller
{
    public function index(Request $request)
    {
        $floors = Room::distinct()->orderBy('floor')->pluck('floor');

        if ($floors->isEmpty()) {
            return redirect()->route('admin.rooms.index')
                ->with('warning', 'Please add rooms first.');
        }

        $currentFloor = $request->floor ?? $floors->first();
        $rooms = Room::with('roomType')
            ->where('floor', $currentFloor)
            ->orderBy('room_number')
            ->get();

        return view('admin.layout-editor.index', compact('floors', 'currentFloor', 'rooms'));
    }

    public function savePositions(Request $request)
    {
        $validated = $request->validate([
            'positions' => 'required|array',
            'positions.*.id' => 'required|exists:rooms,id',
            'positions.*.x' => 'required|integer|min:0|max:50',
            'positions.*.y' => 'required|integer|min:0|max:50',
            'positions.*.w' => 'required|integer|min:1|max:12',
            'positions.*.h' => 'required|integer|min:1|max:12',
        ]);

        foreach ($validated['positions'] as $pos) {
            Room::where('id', $pos['id'])->update([
                'position_x' => $pos['x'],
                'position_y' => $pos['y'],
                'width' => $pos['w'],
                'height' => $pos['h'],
            ]);
        }

        AuditLogger::log('floor_plan.layout_saved', null, 'Floor plan layout updated by admin');

        return response()->json(['success' => true, 'message' => 'Layout saved successfully.']);
    }
}
