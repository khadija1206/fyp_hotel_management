@props([
    'icon' => 'inbox',
    'title' => 'No data',
    'message' => 'There is nothing to show here yet.',
])

<div class="empty-state">
    <div class="empty-state-icon">
        <i class="bi bi-{{ $icon }}"></i>
    </div>
    <div class="empty-state-title">{{ $title }}</div>
    <div class="empty-state-message">{{ $message }}</div>
    @isset($action)
        <div>{{ $action }}</div>
    @endisset
</div>
