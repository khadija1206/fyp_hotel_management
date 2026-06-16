<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomStoreRequest;
use App\Http\Requests\Admin\RoomUpdateRequest;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('roomType');

        if ($request->filled('search')) {
            $query->where('room_number', 'like', "%{$request->search}%");
        }

        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('room_type_id', $request->type);
        }

        $rooms = $query->orderBy('floor')->orderBy('room_number')->paginate(20)->withQueryString();
        $roomTypes = RoomType::active()->orderBy('name')->get();
        $floors = Room::distinct()->orderBy('floor')->pluck('floor');

        return view('admin.rooms.index', compact('rooms', 'roomTypes', 'floors'));
    }

    public function create()
    {
        $roomTypes = RoomType::active()->orderBy('name')->get();
        if ($roomTypes->isEmpty()) {
            return redirect()->route('admin.room-types.create')
                ->with('warning', 'Please create at least one room type before adding rooms.');
        }

        return view('admin.rooms.create', compact('roomTypes'));
    }

    public function store(RoomStoreRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $room = Room::create($data);

        AuditLogger::log('room.created', $room, "Created room: {$room->room_number}");

        return redirect()->route('admin.rooms.index')
            ->with('success', "Room '{$room->room_number}' created.");
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::active()->orderBy('name')->get();

        return view('admin.rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(RoomUpdateRequest $request, Room $room)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', $room->is_active);

        $room->update($data);

        AuditLogger::log('room.updated', $room, "Updated room: {$room->room_number}");

        return redirect()->route('admin.rooms.index')
            ->with('success', "Room '{$room->room_number}' updated.");
    }

    public function destroy(Room $room)
    {
        if ($room->status === 'occupied' || $room->status === 'reserved') {
            return back()->with('error', 'Cannot delete an occupied or reserved room.');
        }

        $num = $room->room_number;
        AuditLogger::log('room.deleted', $room, "Deleted room: {$num}");
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', "Room '{$num}' deleted.");
    }
}
