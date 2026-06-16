@props(['items' => []])

<ul class="breadcrumb-custom">
    @foreach ($items as $label => $url)
        <li>
            @if (!$loop->last && $url)
                <a href="{{ $url }}">{{ $label }}</a>
            @else
                {{ $label }}
            @endif
        </li>
    @endforeach
</ul>
