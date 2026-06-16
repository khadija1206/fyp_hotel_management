@props(['label'])

<div class="d-flex justify-content-between py-2 info-row-divider">
    <span class="text-secondary-custom">{{ $label }}</span>
    <span class="fw-medium">{{ $slot }}</span>
</div>
