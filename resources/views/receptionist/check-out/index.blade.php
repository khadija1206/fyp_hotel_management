<x-app-layout pageTitle="Check-Out Queue">
    <x-page-header title="Check-Out Queue" subtitle="Currently checked-in guests" />

    @if($bookings->isEmpty())
        <x-card>
            <x-empty-state icon="box-arrow-right" title="No guests checked in" message="There are no active stays to check out." />
        </x-card>
    @else
        <x-data-table>
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Check-In</th>
                    <th>Expected Check-Out</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>
                            <strong>{{ $booking->guest->full_name }}</strong>
                            <div><small class="text-secondary-custom">{{ $booking->booking_reference }}</small></div>
                        </td>
                        <td>Room {{ $booking->room->room_number }}</td>
                        <td>{{ formatDate($booking->actual_check_in_at) }}</td>
                        <td>
                            {{ formatDate($booking->check_out_date) }}
                            @if($booking->check_out_date->isPast())
                                <br><x-status-badge status="Overdue" type="danger" />
                            @elseif($booking->check_out_date->isToday())
                                <br><x-status-badge status="Today" type="warning" />
                            @endif
                        </td>
                        <td>{{ formatPKR($booking->total_amount) }}</td>
                        <td>
                            @if($booking->payment_status === 'paid')
                                <x-status-badge status="Paid" type="success" />
                            @else
                                <x-status-badge status="Unpaid" type="danger" />
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('check-out.show', $booking) }}" class="btn btn-primary btn-sm">
                                Check-Out
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-data-table>

        <div class="mt-3">{{ $bookings->links() }}</div>
    @endif
</x-app-layout>
