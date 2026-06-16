<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomTypeStoreRequest;
use App\Http\Requests\Admin\RoomTypeUpdateRequest;
use App\Models\RoomType;
use App\Services\AuditLogger;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')->orderBy('name')->get();

        return view('admin.room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('admin.room-types.create');
    }

    public function store(RoomTypeStoreRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $roomType = RoomType::create($data);

        AuditLogger::log('room_type.created', $roomType, "Created room type: {$roomType->name}");

        return redirect()->route('admin.room-types.index')
            ->with('success', "Room type '{$roomType->name}' created.");
    }

    public function edit(RoomType $roomType)
    {
        return view('admin.room-types.edit', compact('roomType'));
    }

    public function update(RoomTypeUpdateRequest $request, RoomType $roomType)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', $roomType->is_active);

        $roomType->update($data);

        AuditLogger::log('room_type.updated', $roomType, "Updated room type: {$roomType->name}");

        return redirect()->route('admin.room-types.index')
            ->with('success', "Room type '{$roomType->name}' updated.");
    }

    public function destroy(RoomType $roomType)
    {
        if ($roomType->rooms()->exists()) {
            return back()->with('error', "Cannot delete '{$roomType->name}' because rooms are still assigned to it.");
        }

        $name = $roomType->name;
        AuditLogger::log('room_type.deleted', $roomType, "Deleted room type: {$name}");
        $roomType->delete();

        return redirect()->route('admin.room-types.index')
            ->with('success', "Room type '{$name}' deleted.");
    }
}
