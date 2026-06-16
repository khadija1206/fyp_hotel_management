@props([
    'id',
    'title' => 'Confirm Action',
    'message' => 'Are you sure?',
    'confirmText' => 'Confirm',
    'confirmClass' => 'btn-danger',
    'action' => '#',
    'method' => 'POST',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">{{ $message }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ $action }}" class="d-inline">
                    @csrf
                    @if ($method !== 'POST')
                        @method($method)
                    @endif
                    <button type="submit" class="btn {{ $confirmClass }}">{{ $confirmText }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
