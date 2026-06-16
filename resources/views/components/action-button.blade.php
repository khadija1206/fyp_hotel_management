@props([
    'icon' => null,
    'variant' => 'primary',
    'size' => '',
    'href' => null,
    'type' => 'button',
])

@php
    $classes = 'btn btn-' . $variant;
    if ($size) {
        $classes .= ' btn-' . $size;
    }
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon) <i class="bi bi-{{ $icon }}"></i> @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon) <i class="bi bi-{{ $icon }}"></i> @endif
        {{ $slot }}
    </button>
@endif
