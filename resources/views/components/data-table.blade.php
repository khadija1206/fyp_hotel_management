@props([
    'searchable' => false,
    'searchPlaceholder' => 'Search...',
])

<div class="data-table-wrapper">
    @if ($searchable || isset($toolbar))
        <div class="data-table-toolbar">
            @if ($searchable)
                <div class="data-table-search">
                    <input type="text" class="form-control form-control-sm" placeholder="{{ $searchPlaceholder }}" data-table-search />
                </div>
            @endif
            @isset($toolbar)
                <div>{{ $toolbar }}</div>
            @endisset
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            {{ $slot }}
        </table>
    </div>

    @isset($pagination)
        <div class="data-table-toolbar data-table-pagination">
            {{ $pagination }}
        </div>
    @endisset
</div>
