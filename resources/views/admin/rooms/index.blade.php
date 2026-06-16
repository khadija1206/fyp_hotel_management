<x-app-layout pageTitle="Room Management">
    <x-page-header title="Room Management" subtitle="All rooms across all floors">
        <x-slot:actions>
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Room
            </a>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search Room #</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="e.g., 201">
            </div>
            <div class="col-md-2">
                <label class="form-label">Floor</label>
                <select name="floor" class="form-select">
                    <option value="">All</option>
                    @foreach($floors as $floor)
                        <option value="{{ $floor }}" @selected(request('floor') == $floor)>Floor {{ $floor }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All types</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type->id }}" @selected(request('type') == $type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="available" @selected(request('status') === 'available')>Available</option>
                    <option value="occupied" @selected(request('status') === 'occupied')>Occupied</option>
                    <option value="reserved" @selected(request('status') === 'reserved')>Reserved</option>
                    <option value="maintenance" @selected(request('status') === 'maintenance')>Maintenance</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Apply</button>
            </div>
        </form>
    </x-card>

    <x-data-table>
        <thead>
            <tr>
                <th>Room #</th>
                <th>Floor</th>
                <th>Type</th>
                <th>Price/Night</th>
                <th>Status</th>
                <th>Active</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rooms as $room)
                <tr>
                    <td><strong>{{ $room->room_number }}</strong></td>
                    <td>Floor {{ $room->floor }}</td>
                    <td>{{ $room->roomType->name }}</td>
                    <td>{{ formatPKR($room->price_per_night) }}</td>
                    <td><x-status-badge :status="$room->status_label" :type="$room->status_color" /></td>
                    <td>
                        @if($room->is_active)
                            <i class="bi bi-check-circle text-success"></i>
                        @else
                            <i class="bi bi-x-circle text-danger"></i>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-link btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button class="btn btn-link btn-sm text-danger"
                                data-bs-toggle="modal" data-bs-target="#delete-room-{{ $room->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                        <x-confirm-modal
                            id="delete-room-{{ $room->id }}"
                            title="Delete Room?"
                            message="Delete room '{{ $room->room_number }}'? This cannot be undone."
                            action="{{ route('admin.rooms.destroy', $room) }}"
                            method="DELETE"
                            confirmText="Delete"
                        />
                    </td>
                </tr>
            @empty
                <tr><td colspan="7"><x-empty-state icon="door-closed" title="No rooms found" message="Add your first room to get started." /></td></tr>
            @endforelse
        </tbody>
    </x-data-table>

    <div class="mt-3">{{ $rooms->links() }}</div>
</x-app-layout>
