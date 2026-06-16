<aside class="app-sidebar" id="app-sidebar">
    <div class="app-sidebar-brand">
        <a href="{{ route('dashboard') }}">
            <i class="bi bi-building"></i> HMS
        </a>
    </div>

    @if (Auth::user()->isAdmin())
        <div class="app-sidebar-section">Main</div>
        <ul class="app-sidebar-nav">
            <li class="app-sidebar-nav-item">
                <a href="{{ route('dashboard') }}" class="app-sidebar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('admin.rooms.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                    <i class="bi bi-door-closed"></i> Rooms
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('admin.room-types.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('admin.room-types.*') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i> Room Types
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('bookings.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Bookings
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('guests.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('guests.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Guests
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('walk-in.create') }}" class="app-sidebar-nav-link {{ request()->routeIs('walk-in.*') ? 'active' : '' }}">
                    <i class="bi bi-person-walking"></i> Walk-In
                </a>
            </li>
        </ul>

        <div class="app-sidebar-section">Management</div>
        <ul class="app-sidebar-nav">
            <li class="app-sidebar-nav-item">
                <a href="{{ route('admin.users.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i> Users
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="#" class="app-sidebar-nav-link">
                    <i class="bi bi-chat-square-text"></i> Complaints <small class="text-secondary-custom">(soon)</small>
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('admin.reports.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i> Reports
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('admin.settings.edit') }}" class="app-sidebar-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
        </ul>

        <div class="app-sidebar-section">Special</div>
        <ul class="app-sidebar-nav">
            <li class="app-sidebar-nav-item">
                <a href="#" class="app-sidebar-nav-link">
                    <i class="bi bi-grid-3x3"></i> Floor Plan <small class="text-secondary-custom">(soon)</small>
                </a>
            </li>
        </ul>
    @elseif (Auth::user()->isReceptionist())
        <div class="app-sidebar-section">Daily Operations</div>
        <ul class="app-sidebar-nav">
            <li class="app-sidebar-nav-item">
                <a href="{{ route('dashboard') }}" class="app-sidebar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('bookings.create') }}" class="app-sidebar-nav-link {{ request()->routeIs('bookings.create') ? 'active' : '' }}">
                    <i class="bi bi-calendar-plus"></i> New Booking
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('walk-in.create') }}" class="app-sidebar-nav-link {{ request()->routeIs('walk-in.*') ? 'active' : '' }}">
                    <i class="bi bi-person-walking"></i> Walk-In
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('check-in.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('check-in.*') ? 'active' : '' }}">
                    <i class="bi bi-box-arrow-in-right"></i> Check-Ins
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('check-out.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('check-out.*') ? 'active' : '' }}">
                    <i class="bi bi-box-arrow-right"></i> Check-Outs
                </a>
            </li>
        </ul>

        <div class="app-sidebar-section">Records</div>
        <ul class="app-sidebar-nav">
            <li class="app-sidebar-nav-item">
                <a href="{{ route('bookings.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('bookings.index') || request()->routeIs('bookings.show') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> All Bookings
                </a>
            </li>
            <li class="app-sidebar-nav-item">
                <a href="{{ route('guests.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('guests.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Guests
                </a>
            </li>
        </ul>
    @endif

    <div class="app-sidebar-user">
        <div class="app-sidebar-user-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="app-sidebar-user-info">
            <p class="app-sidebar-user-name">{{ Auth::user()->name }}</p>
            <p class="app-sidebar-user-role">{{ ucfirst(Auth::user()->role) }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0 sidebar-logout-btn" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</aside>
