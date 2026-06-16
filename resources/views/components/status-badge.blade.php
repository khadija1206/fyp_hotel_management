@props([
    'status',
    'type' => 'neutral',
])

<span class="status-badge {{ $type }}">
    {{ $status }}
</span>
