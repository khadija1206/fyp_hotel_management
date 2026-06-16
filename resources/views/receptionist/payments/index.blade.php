<x-app-layout pageTitle="Payments">
    <x-page-header title="Payments" subtitle="All transactions">
        <x-slot:actions>
            <a href="{{ route('bookings.index') }}" class="btn btn-primary">
                <i class="bi bi-calendar-check"></i> Go to Bookings to Add Payment
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <x-stat-card label="Total Received" :value="formatPKR($summary['total_received'])" icon="cash-coin" accent />
        </div>
        <div class="col-md-4">
            <x-stat-card label="Total Refunded" :value="formatPKR($summary['total_refunded'])" icon="arrow-return-left" />
        </div>
        <div class="col-md-4">
            <x-stat-card label="Transactions" :value="$summary['transaction_count']" icon="receipt" />
        </div>
    </div>

    <x-card>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Method</label>
                <select name="method" class="form-select">
                    <option value="">All</option>
                    <option value="cash" @selected(request('method') === 'cash')>Cash</option>
                    <option value="card" @selected(request('method') === 'card')>Card</option>
                    <option value="bank_transfer" @selected(request('method') === 'bank_transfer')>Bank Transfer</option>
                    <option value="mobile_wallet" @selected(request('method') === 'mobile_wallet')>Mobile Wallet</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All</option>
                    <option value="payment" @selected(request('type') === 'payment')>Payment</option>
                    <option value="refund" @selected(request('type') === 'refund')>Refund</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
            </div>
        </form>
    </x-card>

    <x-data-table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Date</th>
                <th>Guest</th>
                <th>Booking</th>
                <th>Method</th>
                <th>Type</th>
                <th class="text-end">Amount</th>
                <th>Received By</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $p)
                <tr class="{{ $p->isVoided() ? 'text-secondary-custom' : '' }}">
                    <td>
                        <strong>{{ $p->payment_reference }}</strong>
                        @if($p->isVoided())<br><x-status-badge status="Voided" type="danger" />@endif
                    </td>
                    <td>{{ formatDate($p->payment_date) }}</td>
                    <td>{{ $p->guest->full_name }}</td>
                    <td><a href="{{ route('bookings.show', $p->booking) }}">{{ $p->booking->booking_reference }}</a></td>
                    <td><i class="bi bi-{{ $p->method_icon }}"></i> {{ $p->method_label }}</td>
                    <td>
                        @if($p->type === 'refund')
                            <x-status-badge status="Refund" type="warning" />
                        @else
                            <x-status-badge status="Payment" type="success" />
                        @endif
                    </td>
                    <td class="text-end">
                        <strong>{{ $p->type === 'refund' ? '-' : '' }}{{ formatPKR($p->amount) }}</strong>
                    </td>
                    <td><small>{{ $p->receivedBy?->name }}</small></td>
                    <td class="text-end">
                        <a href="{{ route('payments.show', $p) }}" class="btn btn-link btn-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9"><x-empty-state icon="receipt" title="No payments yet" message="Payments will appear here once recorded." /></td></tr>
            @endforelse
        </tbody>
    </x-data-table>

    <div class="mt-3">{{ $payments->links() }}</div>
</x-app-layout>
