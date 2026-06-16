<x-guest-portal-layout title="My Bookings">
    <x-page-header title="My Bookings" subtitle="All your reservations and stays" />

    <x-card>
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-10">
                <select name="status" class="form-select">
                    <option value="">All bookings</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Upcoming (Confirmed)</option>
                    <option value="checked_in" @selected(request('status') === 'checked_in')>Currently Staying</option>
                    <option value="checked_out" @selected(request('status') === 'checked_out')>Past Stays</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        @if($bookings->isEmpty())
            <x-empty-state icon="calendar-x" title="No bookings yet" message="You don't have any bookings matching this filter." />
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Room</th>
                            <th>Check-In → Check-Out</th>
                            <th>Nights</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $b)
                            <tr>
                                <td><strong>{{ $b->booking_reference }}</strong></td>
                                <td>
                                    Room {{ $b->room->room_number }}
                                    <div><small class="text-secondary-custom">{{ $b->room->roomType->name }}</small></div>
                                </td>
                                <td>{{ formatDate($b->check_in_date) }} → {{ formatDate($b->check_out_date) }}</td>
                                <td>{{ $b->num_nights }}</td>
                                <td>{{ formatPKR($b->total_amount) }}</td>
                                <td><x-status-badge :status="$b->status_label" :type="$b->status_color" /></td>
                                <td class="text-end">
                                    <a href="{{ route('guest.bookings.show', $b) }}" class="btn btn-link btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

    <div class="mt-3">{{ $bookings->links() }}</div>
</x-guest-portal-layout>
