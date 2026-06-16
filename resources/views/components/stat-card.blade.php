@props([
    'label',
    'value',
    'icon' => null,
    'meta' => null,
    'metaType' => 'neutral',
    'accent' => false,
])

<div class="stat-card {{ $accent ? 'accent' : '' }}">
    @if ($icon)
        <div class="stat-card-icon">
            <i class="bi bi-{{ $icon }}"></i>
        </div>
    @endif
    <div class="stat-card-label">{{ $label }}</div>
    <div class="stat-card-value">{{ $value }}</div>
    @if ($meta)
        <div class="stat-card-meta {{ $metaType }}">
            @if ($metaType === 'positive') <i class="bi bi-arrow-up"></i>
            @elseif ($metaType === 'negative') <i class="bi bi-arrow-down"></i> @endif
            {{ $meta }}
        </div>
    @endif
</div>
