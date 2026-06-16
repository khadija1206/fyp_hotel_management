<x-app-layout pageTitle="Floor Plan">
    <x-card>
        <x-empty-state icon="building" title="No rooms yet"
                       message="Add rooms first to use the floor plan dashboard.">
            <x-slot:action>
                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">Add Rooms</a>
            </x-slot:action>
        </x-empty-state>
    </x-card>
</x-app-layout>
