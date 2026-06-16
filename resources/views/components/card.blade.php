@props(['title' => null, 'subtitle' => null])

<div class="card mb-4">
    @if ($title)
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">{{ $title }}</div>
                    @if ($subtitle)
                        <small class="text-secondary-custom">{{ $subtitle }}</small>
                    @endif
                </div>
                @isset($headerActions)
                    <div>{{ $headerActions }}</div>
                @endisset
            </div>
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
