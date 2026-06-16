<x-app-layout pageTitle="Room Types">
    <x-page-header title="Room Types" subtitle="Configure room categories and pricing">
        <x-slot:actions>
            <a href="{{ route('admin.room-types.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Room Type
            </a>
        </x-slot:actions>
    </x-page-header>

    @if($roomTypes->isEmpty())
        <x-card>
            <x-empty-state icon="grid" title="No room types yet"
                           message="Create room categories like Single, Double, Suite to organize your rooms.">
                <x-slot:action>
                    <a href="{{ route('admin.room-types.create') }}" class="btn btn-primary">Add First Room Type</a>
                </x-slot:action>
            </x-empty-state>
        </x-card>
    @else
        <div class="row g-3">
            @foreach($roomTypes as $type)
                <div class="col-md-6 col-lg-4">
                    <x-card>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h3 class="mb-1">{{ $type->name }}</h3>
                                @if($type->is_active)
                                    <x-status-badge status="Active" type="success" />
                                @else
                                    <x-status-badge status="Inactive" type="danger" />
                                @endif
                            </div>
                            <span class="text-primary fw-bold">{{ formatPKR($type->base_price) }}<small class="text-secondary-custom">/night</small></span>
                        </div>

                        @if($type->description)
                            <p class="text-secondary-custom small mb-3">{{ $type->description }}</p>
                        @endif

                        <x-info-row label="Capacity">{{ $type->capacity }} guest{{ $type->capacity > 1 ? 's' : '' }}</x-info-row>
                        <x-info-row label="Bed Layout">{{ ucfirst($type->bed_layout) }} ({{ $type->bed_count }} bed{{ $type->bed_count > 1 ? 's' : '' }})</x-info-row>
                        <x-info-row label="Rooms Assigned">{{ $type->rooms_count }}</x-info-row>

                        @if($type->amenities)
                            <div class="mt-3">
                                <small class="text-secondary-custom">Amenities:</small>
                                <div class="mt-1">
                                    @foreach($type->amenities_array as $amenity)
                                        <span class="badge bg-subtle text-dark me-1">{{ $amenity }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('admin.room-types.edit', $type) }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button class="btn btn-link btn-sm text-danger"
                                    data-bs-toggle="modal" data-bs-target="#delete-type-{{ $type->id }}">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>

                        <x-confirm-modal
                            id="delete-type-{{ $type->id }}"
                            title="Delete Room Type?"
                            message="Are you sure you want to delete '{{ $type->name }}'?"
                            action="{{ route('admin.room-types.destroy', $type) }}"
                            method="DELETE"
                            confirmText="Delete"
                        />
                    </x-card>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
