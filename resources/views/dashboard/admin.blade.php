<x-app-layout pageTitle="Admin Dashboard">
    <x-page-header title="Admin Dashboard" subtitle="Welcome back, {{ Auth::user()->name }}">
        <x-slot:actions>
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Room
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Total Rooms" :value="$totalRooms" icon="door-closed" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Occupancy Rate" :value="$occupancyRate . '%'" icon="building-fill-check"
                         :meta="$occupiedRooms . ' of ' . $totalRooms . ' occupied'" accent />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Available Now" :value="$availableRooms" icon="door-open" meta="Ready to book" metaType="positive" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="In Maintenance" :value="$maintenanceRooms" icon="tools" meta="Out of service" metaType="negative" />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <x-card title="Occupancy by Floor" subtitle="Real-time room distribution">
                @if($roomsByFloor->isEmpty())
                    <x-empty-state icon="building" title="No rooms yet" message="Add rooms to see floor breakdown.">
                        <x-slot:action>
                            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">Add Room</a>
                        </x-slot:action>
                    </x-empty-state>
                @else
                    @foreach($roomsByFloor as $floor)
                        @php
                            $pct = $floor->total > 0 ? round(($floor->occupied / $floor->total) * 100) : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <strong>Floor {{ $floor->floor }}</strong>
                                <span class="text-secondary-custom small">{{ $floor->occupied }}/{{ $floor->total }} ({{ $pct }}%)</span>
                            </div>
                            <div class="progress" style="height: 8px; background-color: var(--color-border-light);">
                                <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%; background-color: var(--color-primary);"></div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </x-card>

            <x-card title="Room Status Breakdown">
                <div class="row g-3">
                    @foreach(['available' => 'success', 'occupied' => 'danger', 'reserved' => 'warning', 'maintenance' => 'neutral'] as $status => $type)
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-3" style="background: var(--color-bg-subtle); border-radius: var(--radius-md);">
                                <div style="font-size: var(--text-2xl); font-weight: 700; color: var(--color-text-primary);">
                                    {{ $roomsByStatus[$status] }}
                                </div>
                                <x-status-badge :status="ucfirst($status)" :type="$type" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        </div>

        <div class="col-lg-5">
            <x-card title="Quick Stats">
                <x-info-row label="Active Staff">{{ $totalStaff }}</x-info-row>
                <x-info-row label="Registered Guests">{{ $totalGuests }}</x-info-row>
                <x-info-row label="Pending Bookings"><span class="text-secondary-custom">Coming soon</span></x-info-row>
                <x-info-row label="Open Complaints">
                    @if($complaintStats['total_open'] > 0)
                        <span class="text-danger">{{ $complaintStats['total_open'] }}</span>
                        @if($complaintStats['high_priority'] > 0)
                            <small class="text-secondary-custom">({{ $complaintStats['high_priority'] }} high priority)</small>
                        @endif
                    @else
                        <span class="text-success">0</span>
                    @endif
                </x-info-row>
                <x-info-row label="Resolved This Week">{{ $complaintStats['resolved_this_week'] }}</x-info-row>
            </x-card>

            <x-card title="Recent Activity" subtitle="Last 8 system actions">
                @if($recentActivity->isEmpty())
                    <p class="text-secondary-custom mb-0">No activity yet.</p>
                @else
                    @foreach($recentActivity as $log)
                        <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid var(--color-border-light);">
                            <div>
                                <div style="font-size: var(--text-sm);">{{ $log->description ?? $log->action }}</div>
                                <small class="text-secondary-custom">{{ $log->user->name ?? 'System' }}</small>
                            </div>
                            <small class="text-secondary-custom">{{ $log->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
