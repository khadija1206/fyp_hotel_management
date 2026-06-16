@foreach($rooms as $index => $room)
    @php
        $hasLayout = $room->width > 0 && $room->height > 0;
        $x = $hasLayout ? $room->position_x : ($index % 4) * 3;
        $y = $hasLayout ? $room->position_y : (int) floor($index / 4) * 2;
        $colSpan = $hasLayout ? $room->width : 3;
        $rowSpan = $hasLayout ? $room->height : 2;
    @endphp
    <div class="fp-room fp-{{ $room->status }}"
         data-room-id="{{ $room->id }}"
         style="grid-column: {{ $x + 1 }} / span {{ $colSpan }}; grid-row: {{ $y + 1 }} / span {{ $rowSpan }};"
         tabindex="0"
         role="button"
         aria-label="Room {{ $room->room_number }}, status: {{ $room->status }}">
        <div class="fp-room-num">{{ $room->room_number }}</div>
        <div class="fp-room-bed">
            @include('floorplan.partials.bed-icon', ['layout' => $room->roomType->bed_layout])
        </div>
        <div class="fp-room-status">{{ $room->status_label }}</div>
    </div>
@endforeach

<div class="fp-corridor" style="grid-column: 1 / -1;">corridor</div>
