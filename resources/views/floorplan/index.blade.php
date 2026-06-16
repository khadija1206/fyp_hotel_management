<x-app-layout pageTitle="Floor Plan">
    <link rel="stylesheet" href="{{ asset('css/floor-plan.css') }}">

    <x-page-header title="Floor Plan" subtitle="Visual overview of all rooms and their current status">
        <x-slot:actions>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.layout-editor.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrows-move"></i> Layout Editor
                </a>
            @endif
            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                <i class="bi bi-calendar-plus"></i> New Booking
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="fp-tabs" id="fp-tabs">
        @foreach($floors as $floor)
            <button type="button" class="fp-tab {{ $floor == $currentFloor ? 'active' : '' }}" data-floor="{{ $floor }}">
                <i class="bi bi-layers"></i> Floor {{ $floor }}
            </button>
        @endforeach
    </div>

    <div class="fp-stats" id="fp-stats">
        <div class="fp-stat-pill">
            <span class="fp-stat-pill-label">
                <span class="fp-stat-dot" style="background-color: var(--color-success);"></span>
                Available
            </span>
            <span class="fp-stat-pill-value" data-stat="available">{{ $stats['available'] }}</span>
        </div>
        <div class="fp-stat-pill">
            <span class="fp-stat-pill-label">
                <span class="fp-stat-dot" style="background-color: var(--color-danger);"></span>
                Occupied
            </span>
            <span class="fp-stat-pill-value" data-stat="occupied">{{ $stats['occupied'] }}</span>
        </div>
        <div class="fp-stat-pill">
            <span class="fp-stat-pill-label">
                <span class="fp-stat-dot" style="background-color: var(--color-warning);"></span>
                Reserved
            </span>
            <span class="fp-stat-pill-value" data-stat="reserved">{{ $stats['reserved'] }}</span>
        </div>
        <div class="fp-stat-pill">
            <span class="fp-stat-pill-label">
                <span class="fp-stat-dot" style="background-color: var(--color-neutral);"></span>
                Maintenance
            </span>
            <span class="fp-stat-pill-value" data-stat="maintenance">{{ $stats['maintenance'] }}</span>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="fp-container" id="fp-container" data-current-floor="{{ $currentFloor }}">
                <div class="fp-poll-indicator">
                    <span class="fp-poll-dot"></span>
                    <span>Live</span>
                </div>
                <div class="fp-floor-label">Floor {{ $currentFloor }}</div>

                <div class="fp-grid" id="fp-grid">
                    @include('floorplan.partials.rooms', ['rooms' => $rooms])
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="fp-side-panel" id="fp-side-panel">
                <div class="fp-side-panel-empty">
                    <i class="bi bi-hand-index-thumb"></i>
                    <h5>Select a Room</h5>
                    <p class="mb-0">Click any room on the floor plan to see details and available actions.</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/floor-plan.js') }}"></script>
    @endpush
</x-app-layout>
