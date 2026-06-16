<x-app-layout pageTitle="Add Room Type">
    <x-breadcrumb :items="[
        'Dashboard' => route('dashboard'),
        'Room Types' => route('admin.room-types.index'),
        'Add Room Type' => null,
    ]" />

    <x-page-header title="Add Room Type" />

    <x-card>
        <form method="POST" action="{{ route('admin.room-types.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <x-form-field label="Name" name="name" required placeholder="e.g., Deluxe Single" />
                </div>
                <div class="col-md-4">
                    <x-form-field label="Base Price (PKR)" name="base_price" type="number" required placeholder="4500" />
                </div>
                <div class="col-12">
                    <x-form-field label="Description" name="description" placeholder="Short description (optional)" />
                </div>
                <div class="col-md-4">
                    <x-form-field label="Capacity (guests)" name="capacity" type="number" :value="1" required />
                </div>
                <div class="col-md-4">
                    <x-form-field label="Bed Count" name="bed_count" type="number" :value="1" required />
                </div>
                <div class="col-md-4">
                    <x-form-select label="Bed Layout" name="bed_layout" required
                                   :options="['single' => 'Single', 'double' => 'Double', 'twin' => 'Twin', 'suite' => 'Suite']" />
                </div>
                <div class="col-12">
                    <x-form-field label="Amenities" name="amenities"
                                  hint="Comma-separated list, e.g., WiFi, AC, TV, Mini Fridge" />
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="is_active">
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Create</button>
                <a href="{{ route('admin.room-types.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
