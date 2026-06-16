<x-app-layout pageTitle="Reception Dashboard">
    <x-page-header title="Reception Dashboard" subtitle="Daily operations at a glance">
        <x-slot:actions>
            <a href="#" class="btn btn-primary"><i class="bi bi-calendar-plus"></i> New Booking</a>
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Available Rooms" :value="$availableRooms" icon="door-open" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Occupied Rooms" :value="$occupiedRooms" icon="door-closed" />
        </div>
    </div>

    <x-card title="Today's Schedule" subtitle="Check-ins and check-outs for today">
        <x-empty-state
            icon="calendar2-day"
            title="No schedule yet"
            message="Today's check-ins and check-outs will appear here when booking module is ready."
        />
    </x-card>
</x-app-layout>
