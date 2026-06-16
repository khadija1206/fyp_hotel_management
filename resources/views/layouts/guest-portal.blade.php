<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'My Portal' }} — {{ config('app.name', 'Hotel Management System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700|jetbrains-mono:400,500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="guest-layout">
        <div class="guest-topbar">
            <div class="guest-topbar-inner">
                <a href="{{ route('guest.dashboard') }}" class="guest-topbar-brand">
                    <i class="bi bi-building"></i> {{ config('app.name') }}
                </a>

                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle p-0 guest-topbar-user-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('guest.profile.edit') }}"><i class="bi bi-person"></i> My Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('guest.bookings.index') }}"><i class="bi bi-calendar-check"></i> My Bookings</a></li>
                        <li><a class="dropdown-item" href="{{ route('guest.bills.index') }}"><i class="bi bi-receipt"></i> My Bills</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="guest-content">
            @if (session('success'))
                <div class="d-none" data-toast data-type="success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="d-none" data-toast data-type="danger">{{ session('error') }}</div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </div>

    <div class="toast-container" id="toast-container"></div>

    @stack('scripts')
</body>
</html>
