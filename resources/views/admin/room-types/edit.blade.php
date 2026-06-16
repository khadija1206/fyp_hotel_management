<x-app-layout pageTitle="Edit Room Type">
    <x-breadcrumb :items="[
        'Dashboard' => route('dashboard'),
        'Room Types' => route('admin.room-types.index'),
        'Edit' => null,
    ]" />

    <x-page-header title="Edit Room Type" subtitle="{{ $roomType->name }}" />

    <x-card>
        <form method="POST" action="{{ route('admin.room-types.update', $roomType) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <x-form-field label="Name" name="name" :value="$roomType->name" required />
                </div>
                <div class="col-md-4">
                    <x-form-field label="Base Price (PKR)" name="base_price" type="number" :value="$roomType->base_price" required />
                </div>
                <div class="col-12">
                    <x-form-field label="Description" name="description" :value="$roomType->description" />
                </div>
                <div class="col-md-4">
                    <x-form-field label="Capacity" name="capacity" type="number" :value="$roomType->capacity" required />
                </div>
                <div class="col-md-4">
                    <x-form-field label="Bed Count" name="bed_count" type="number" :value="$roomType->bed_count" required />
                </div>
                <div class="col-md-4">
                    <x-form-select label="Bed Layout" name="bed_layout" required :selected="$roomType->bed_layout"
                                   :options="['single' => 'Single', 'double' => 'Double', 'twin' => 'Twin', 'suite' => 'Suite']" />
                </div>
                <div class="col-12">
                    <x-form-field label="Amenities" name="amenities" :value="$roomType->amenities" />
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               {{ $roomType->is_active ? 'checked' : '' }} id="is_active">
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
                <a href="{{ route('admin.room-types.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
