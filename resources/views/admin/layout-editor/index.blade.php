<x-app-layout pageTitle="Layout Editor">
    <link rel="stylesheet" href="{{ asset('css/floor-plan.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridstack@10.3.1/dist/gridstack.min.css">

    <x-breadcrumb :items="[
        'Dashboard' => route('dashboard'),
        'Floor Plan' => route('floor-plan.index'),
        'Layout Editor' => null,
    ]" />

    <x-page-header title="Floor Layout Editor"
                   subtitle="Drag rooms to arrange them on the floor. Resize from the bottom-right corner.">
        <x-slot:actions>
            <a href="{{ route('floor-plan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Floor Plan
            </a>
            <button id="save-layout-btn" class="btn btn-primary">
                <i class="bi bi-cloud-arrow-up"></i> Save Layout
            </button>
        </x-slot:actions>
    </x-page-header>

    <div class="editor-help">
        <strong><i class="bi bi-info-circle"></i> How to use:</strong>
        <ul class="mb-0 mt-2" style="padding-left: 1.5rem;">
            <li>Drag any room to move it on the floor grid</li>
            <li>Resize rooms from the corner handles</li>
            <li>Larger rooms (suites) can be made wider/taller for visual hierarchy</li>
            <li>Click "Save Layout" to persist changes — they appear immediately on the Floor Plan dashboard</li>
        </ul>
    </div>

    <div class="fp-tabs">
        @foreach($floors as $floor)
            <a href="{{ route('admin.layout-editor.index', ['floor' => $floor]) }}"
               class="fp-tab {{ $floor == $currentFloor ? 'active' : '' }}">
                <i class="bi bi-layers"></i> Floor {{ $floor }}
            </a>
        @endforeach
    </div>

    <div class="layout-editor-container">
        <div class="grid-stack" id="layout-grid">
            @foreach($rooms as $room)
                @php
                    $hasLayout = $room->width > 0 && $room->height > 0;
                    $x = $hasLayout ? $room->position_x : ($loop->index % 4) * 3;
                    $y = $hasLayout ? $room->position_y : (int) floor($loop->index / 4) * 2;
                    $w = $hasLayout ? $room->width : 3;
                    $h = $hasLayout ? $room->height : 2;
                @endphp
                <div class="grid-stack-item"
                     gs-x="{{ $x }}" gs-y="{{ $y }}" gs-w="{{ $w }}" gs-h="{{ $h }}"
                     data-room-id="{{ $room->id }}">
                    <div class="grid-stack-item-content editor-{{ $room->status }}">
                        <div class="editor-room-num">{{ $room->room_number }}</div>
                        <div class="editor-room-type">{{ $room->roomType->name }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <meta name="layout-save-url" content="{{ route('admin.layout-editor.save') }}">

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/gridstack@10.3.1/dist/gridstack-all.js"></script>
        <script src="{{ asset('js/layout-editor.js') }}"></script>
    @endpush
</x-app-layout>
