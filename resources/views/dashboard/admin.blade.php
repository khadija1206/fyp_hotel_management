<x-app-layout pageTitle="Admin Dashboard">
    <x-page-header title="Admin Dashboard" subtitle="Welcome back, {{ Auth::user()->name }}">
        <x-slot:actions>
            <a href="#" class="btn btn-secondary"><i class="bi bi-download"></i> Export</a>
            <a href="#" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Quick Action</a>
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Total Rooms" value="0" icon="door-closed" meta="Awaiting data" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Occupied" value="0" icon="person-check" meta="0% occupancy" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Today's Bookings" value="0" icon="calendar-check" meta="No bookings yet" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Revenue (Month)" value="PKR 0" icon="cash-stack" meta="Awaiting data" />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <x-card title="Recent Bookings" subtitle="Last 10 bookings will appear here">
                <x-empty-state
                    icon="calendar-x"
                    title="No bookings yet"
                    message="Bookings will appear here once the booking module is built."
                />
            </x-card>
        </div>
        <div class="col-lg-4">
            <x-card title="Quick Stats">
                <x-info-row label="Active Guests">0</x-info-row>
                <x-info-row label="Pending Complaints">0</x-info-row>
                <x-info-row label="Rooms in Maintenance">0</x-info-row>
                <x-info-row label="Today's Check-ins">0</x-info-row>
            </x-card>
        </div>
    </div>
</x-app-layout>
