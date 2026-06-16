<x-guest-portal-layout title="My Bills">
    <x-page-header title="My Bills" subtitle="Bills from all your stays" />

    <x-card>
        @if($bookings->isEmpty())
            <x-empty-state icon="receipt" title="No bills yet" message="Your bills will appear here once you have a booking." />
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Stay Period</th>
                            <th>Room</th>
                            <th>Total</th>
                            <th>Payment Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $b)
                            <tr>
                                <td><strong>{{ $b->booking_reference }}</strong></td>
                                <td>{{ formatDate($b->check_in_date) }} → {{ formatDate($b->check_out_date) }}</td>
                                <td>Room {{ $b->room->room_number }}</td>
                                <td>{{ formatPKR($b->total_amount) }}</td>
                                <td>
                                    @if($b->payment_status === 'paid')
                                        <x-status-badge status="Paid" type="success" />
                                    @elseif($b->payment_status === 'partial')
                                        <x-status-badge status="Partial" type="warning" />
                                    @else
                                        <x-status-badge status="Unpaid" type="danger" />
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('guest.bills.show', $b) }}" class="btn btn-link btn-sm">
                                        <i class="bi bi-eye"></i> View Bill
                                    </a>
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
