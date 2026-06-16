<x-app-layout pageTitle="Reception Dashboard">
    <x-page-header title="Reception Dashboard" subtitle="Welcome back, {{ Auth::user()->name }}">
        <x-slot:actions>
            <a href="{{ route('walk-in.create') }}" class="btn btn-secondary">
                <i class="bi bi-person-walking"></i> Walk-In
            </a>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                <i class="bi bi-calendar-plus"></i> New Booking
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3 mb-4">
        <div class="col-md col-sm-6">
            <x-stat-card label="Available Rooms" :value="$stats['available_rooms']" icon="door-open" />
        </div>
        <div class="col-md col-sm-6">
            <x-stat-card label="Occupied" :value="$stats['occupied_rooms']" icon="person-check" />
        </div>
        <div class="col-md col-sm-6">
            <x-stat-card label="Today's Check-Ins" :value="$stats['todays_check_ins']" icon="box-arrow-in-right" accent />
        </div>
        <div class="col-md col-sm-6">
            <x-stat-card label="Today's Check-Outs" :value="$stats['todays_check_outs']" icon="box-arrow-right" />
        </div>
        <div class="col-md col-sm-6">
            <x-stat-card label="Walk-Ins Today" :value="$stats['walk_ins_today']" icon="person-walking" />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <x-card title="Pending Check-Ins" subtitle="Bookings ready for check-in">
                @if($pendingCheckIns->isEmpty())
                    <x-empty-state icon="check2-circle" title="All clear" message="No pending check-ins right now." />
                @else
                    @foreach($pendingCheckIns as $b)
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid var(--color-border-light);">
                            <div>
                                <strong>{{ $b->guest->full_name }}</strong>
                                <div><small class="text-secondary-custom">Room {{ $b->room->room_number }} — {{ $b->booking_reference }}</small></div>
                            </div>
                            <form method="POST" action="{{ route('check-in.process', $b) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm">Check-In</button>
                            </form>
                        </div>
                    @endforeach
                    <div class="mt-3"><a href="{{ route('check-in.index') }}">View all →</a></div>
                @endif
            </x-card>
        </div>

        <div class="col-lg-6">
            <x-card title="Today's Check-Outs" subtitle="Guests scheduled to leave today">
                @if($pendingCheckOuts->isEmpty())
                    <x-empty-state icon="check2-circle" title="All clear" message="No check-outs scheduled for today." />
                @else
                    @foreach($pendingCheckOuts as $b)
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid var(--color-border-light);">
                            <div>
                                <strong>{{ $b->guest->full_name }}</strong>
                                <div><small class="text-secondary-custom">Room {{ $b->room->room_number }} — {{ formatPKR($b->total_amount) }}</small></div>
                            </div>
                            <a href="{{ route('check-out.show', $b) }}" class="btn btn-primary btn-sm">Check-Out</a>
                        </div>
                    @endforeach
                    <div class="mt-3"><a href="{{ route('check-out.index') }}">View all →</a></div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
