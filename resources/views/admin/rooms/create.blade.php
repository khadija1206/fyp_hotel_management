<x-app-layout pageTitle="Add Room">
    <x-breadcrumb :items="[
        'Dashboard' => route('dashboard'),
        'Rooms' => route('admin.rooms.index'),
        'Add Room' => null,
    ]" />

    <x-page-header title="Add Room" />

    <x-card>
        <form method="POST" action="{{ route('admin.rooms.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <x-form-field label="Room Number" name="room_number" required placeholder="e.g., 201" />
                </div>
                <div class="col-md-4">
                    <x-form-field label="Floor" name="floor" type="number" required placeholder="1" />
                </div>
                <div class="col-md-4">
                    <x-form-select label="Room Type" name="room_type_id" required
                                   :options="$roomTypes->pluck('name', 'id')->toArray()" />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Price per Night (PKR)" name="price_per_night" type="number" required
                                  hint="Can override the room type's base price" />
                </div>
                <div class="col-md-6">
                    <x-form-select label="Status" name="status" required
                                   :selected="'available'"
                                   :options="[
                                       'available' => 'Available',
                                       'occupied' => 'Occupied',
                                       'reserved' => 'Reserved',
                                       'maintenance' => 'Maintenance',
                                   ]" />
                </div>
                <div class="col-12">
                    <x-form-field label="Notes" name="notes" hint="Optional: maintenance issues, special features, etc." />
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="is_active">
                        <label class="form-check-label" for="is_active">Room is active and bookable</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Create Room</button>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
