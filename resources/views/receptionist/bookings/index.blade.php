<x-app-layout pageTitle="All Bookings">
    <x-page-header title="All Bookings" subtitle="Reservation management">
        <x-slot:actions>
            <a href="{{ route('walk-in.create') }}" class="btn btn-secondary">
                <i class="bi bi-person-walking"></i> Walk-In
            </a>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                <i class="bi bi-calendar-plus"></i> New Booking
            </a>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Ref #, name, phone..." class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                    <option value="checked_in" @selected(request('status') === 'checked_in')>Checked In</option>
                    <option value="checked_out" @selected(request('status') === 'checked_out')>Checked Out</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Apply</button>
            </div>
        </form>
    </x-card>

    <x-data-table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Guest</th>
                <th>Room</th>
                <th>Check-In → Check-Out</th>
                <th>Nights</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>
                        <a href="{{ route('bookings.show', $booking) }}" class="fw-bold">{{ $booking->booking_reference }}</a>
                        @if($booking->is_walk_in)<br><small class="badge bg-subtle text-dark">Walk-In</small>@endif
                    </td>
                    <td>
                        <a href="{{ route('guests.show', $booking->guest) }}">{{ $booking->guest->full_name }}</a>
                        <div><small class="text-secondary-custom">{{ $booking->guest->phone }}</small></div>
                    </td>
                    <td>
                        Room <strong>{{ $booking->room->room_number }}</strong>
                        <div><small class="text-secondary-custom">{{ $booking->room->roomType->name }}</small></div>
                    </td>
                    <td>
                        {{ formatDate($booking->check_in_date) }}
                        <i class="bi bi-arrow-right small text-secondary-custom"></i>
                        {{ formatDate($booking->check_out_date) }}
                    </td>
                    <td>{{ $booking->num_nights }}</td>
                    <td>{{ formatPKR($booking->total_amount) }}</td>
                    <td><x-status-badge :status="$booking->status_label" :type="$booking->status_color" /></td>
                    <td class="text-end">
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-link btn-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8"><x-empty-state icon="calendar-x" title="No bookings found" message="Create a new booking or adjust filters." /></td></tr>
            @endforelse
        </tbody>
    </x-data-table>

    <div class="mt-3">{{ $bookings->links() }}</div>
</x-app-layout>
