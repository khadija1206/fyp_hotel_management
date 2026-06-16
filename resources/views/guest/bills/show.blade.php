<x-guest-portal-layout title="Bill — {{ $booking->booking_reference }}">
    <x-breadcrumb :items="[
        'My Bills' => route('guest.bills.index'),
        $booking->booking_reference => null,
    ]" />

    <div class="d-flex justify-content-between mb-3 d-print-none">
        <div>
            <h1 class="mb-0">Bill — {{ $booking->booking_reference }}</h1>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print Bill
            </button>
            <a href="{{ route('guest.bills.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="bg-white rounded p-5 shadow-sm" id="printable-bill" style="max-width: 800px; margin: 0 auto;">

        <div class="d-flex justify-content-between border-bottom pb-3 mb-4">
            <div>
                <h2 class="mb-1" style="color: var(--color-primary);">{{ $hotel['name'] }}</h2>
                <small class="text-secondary-custom">
                    {{ $hotel['address'] }}<br>
                    {{ $hotel['phone'] }} | {{ $hotel['email'] }}
                </small>
            </div>
            <div class="text-end">
                <h3 class="mb-1">INVOICE</h3>
                <small class="text-secondary-custom">
                    Ref: <strong>{{ $booking->booking_reference }}</strong><br>
                    Date: {{ formatDate(now()) }}
                </small>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="text-secondary-custom">Bill To</h5>
                <strong>{{ $booking->guest->full_name }}</strong><br>
                {{ $booking->guest->phone }}<br>
                @if($booking->guest->email) {{ $booking->guest->email }}<br> @endif
                {{ $booking->guest->identity_document }}
            </div>
            <div class="col-md-6 text-md-end">
                <h5 class="text-secondary-custom">Stay Details</h5>
                <strong>Room {{ $booking->room->room_number }}</strong> — {{ $booking->room->roomType->name }}<br>
                Check-In: {{ formatDate($booking->check_in_date) }}<br>
                Check-Out: {{ formatDate($booking->check_out_date) }}<br>
                Nights: {{ $booking->num_nights }}
            </div>
        </div>

        <table class="table">
            <thead style="background-color: var(--color-bg-subtle);">
                <tr>
                    <th>Description</th>
                    <th class="text-end">Rate</th>
                    <th class="text-end">Nights</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Room {{ $booking->room->room_number }} — {{ $booking->room->roomType->name }}
                        <div><small class="text-secondary-custom">{{ formatDate($booking->check_in_date) }} to {{ formatDate($booking->check_out_date) }}</small></div>
                    </td>
                    <td class="text-end">{{ formatPKR($booking->rate_per_night) }}</td>
                    <td class="text-end">{{ $booking->num_nights }}</td>
                    <td class="text-end">{{ formatPKR($booking->subtotal) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end">Subtotal</td>
                    <td class="text-end">{{ formatPKR($booking->subtotal) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end">Tax ({{ $booking->tax_rate }}%)</td>
                    <td class="text-end">{{ formatPKR($booking->tax_amount) }}</td>
                </tr>
                <tr style="background-color: var(--color-bg-subtle);">
                    <td colspan="3" class="text-end"><strong>TOTAL</strong></td>
                    <td class="text-end"><strong style="font-size: var(--text-lg); color: var(--color-primary);">{{ formatPKR($booking->total_amount) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center p-3 rounded mb-3" style="background-color: var(--color-bg-subtle);">
                <strong>Payment Status:</strong>
                @if($booking->payment_status === 'paid')
                    <span style="color: var(--color-success); font-weight: 700;">✓ PAID IN FULL</span>
                @elseif($booking->payment_status === 'partial')
                    <span style="color: var(--color-warning-text); font-weight: 700;">⚠ PARTIALLY PAID</span>
                @else
                    <span style="color: var(--color-danger); font-weight: 700;">✗ UNPAID</span>
                @endif
            </div>

            @if($booking->payments->isNotEmpty())
                <h5>Payment Records</h5>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Date</th><th>Reference</th><th>Method</th><th class="text-end">Amount</th></tr>
                    </thead>
                    <tbody>
                        @foreach($booking->payments as $p)
                            <tr>
                                <td>{{ formatDate($p->payment_date) }}</td>
                                <td><small>{{ $p->payment_reference }}</small></td>
                                <td>{{ $p->method_label }}</td>
                                <td class="text-end">{{ $p->type === 'refund' ? '-' : '' }}{{ formatPKR($p->amount) }}</td>
                            </tr>
                        @endforeach
                        <tr style="border-top: 2px solid var(--color-primary);">
                            <td colspan="3" class="text-end"><strong>Paid:</strong></td>
                            <td class="text-end"><strong>{{ formatPKR($booking->amount_paid) }}</strong></td>
                        </tr>
                        @if($booking->amount_due > 0)
                            <tr>
                                <td colspan="3" class="text-end"><strong>Due:</strong></td>
                                <td class="text-end" style="color: var(--color-danger);"><strong>{{ formatPKR($booking->amount_due) }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endif
        </div>

        <div class="text-center text-secondary-custom small mt-4 pt-3 border-top">
            Thank you for choosing {{ $hotel['name'] }}.<br>
            We hope you enjoyed your stay.
        </div>
    </div>

    @push('scripts')
    <style>
        @media print {
            body { background: white !important; }
            .guest-topbar, .guest-content > .breadcrumb-custom, .d-print-none { display: none !important; }
            #printable-bill { box-shadow: none !important; max-width: 100% !important; padding: 0 !important; }
            .guest-content { padding: 0 !important; }
        }
    </style>
    @endpush
</x-guest-portal-layout>
