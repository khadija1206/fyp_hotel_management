<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — {{ config('app.name', 'Hotel Management System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700|jetbrains-mono:400,500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-layout">
        @include('layouts.partials.sidebar')

        <div class="app-main">
            <div class="app-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Toggle sidebar">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="app-topbar-title">{{ $pageTitle ?? 'Dashboard' }}</h1>
                </div>

                <div class="d-flex align-items-center gap-3">
                    @hasSection('topbar-actions')
                        @yield('topbar-actions')
                    @endif
                </div>
            </div>

            <div class="app-content">
                @if (session('success'))
                    <div class="d-none" data-toast data-type="success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="d-none" data-toast data-type="danger">{{ session('error') }}</div>
                @endif
                @if (session('warning'))
                    <div class="d-none" data-toast data-type="warning">{{ session('warning') }}</div>
                @endif
                @if (session('info'))
                    <div class="d-none" data-toast data-type="info">{{ session('info') }}</div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </div>
    </div>

    <div class="toast-container" id="toast-container"></div>

    @stack('scripts')
</body>
</html>
