<x-app-layout pageTitle="Edit Room">
    <x-breadcrumb :items="[
        'Dashboard' => route('dashboard'),
        'Rooms' => route('admin.rooms.index'),
        'Edit Room' => null,
    ]" />

    <x-page-header title="Edit Room" subtitle="Room {{ $room->room_number }}" />

    <x-card>
        <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4">
                    <x-form-field label="Room Number" name="room_number" :value="$room->room_number" required />
                </div>
                <div class="col-md-4">
                    <x-form-field label="Floor" name="floor" type="number" :value="$room->floor" required />
                </div>
                <div class="col-md-4">
                    <x-form-select label="Room Type" name="room_type_id" required :selected="$room->room_type_id"
                                   :options="$roomTypes->pluck('name', 'id')->toArray()" />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Price per Night (PKR)" name="price_per_night" type="number" :value="$room->price_per_night" required />
                </div>
                <div class="col-md-6">
                    <x-form-select label="Status" name="status" required :selected="$room->status"
                                   :options="[
                                       'available' => 'Available',
                                       'occupied' => 'Occupied',
                                       'reserved' => 'Reserved',
                                       'maintenance' => 'Maintenance',
                                   ]" />
                </div>
                <div class="col-12">
                    <x-form-field label="Notes" name="notes" :value="$room->notes" />
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               {{ $room->is_active ? 'checked' : '' }} id="is_active">
                        <label class="form-check-label" for="is_active">Room is active and bookable</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
