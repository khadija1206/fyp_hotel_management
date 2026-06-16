<x-app-layout pageTitle="Record Payment">
    <x-breadcrumb :items="[
        'Bookings' => route('bookings.index'),
        $booking->booking_reference => route('bookings.show', $booking),
        'Record Payment' => null,
    ]" />

    <x-page-header :title="'Record Payment'" :subtitle="$booking->booking_reference . ' — ' . $booking->guest->full_name" />

    <div class="row g-3">
        <div class="col-lg-7">
            <x-card title="Payment Details">
                <form method="POST" action="{{ route('payments.store', $booking) }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="Amount (PKR)" name="amount" type="number" step="0.01" required
                                          :value="$booking->amount_due" />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Payment Date" name="payment_date" type="date"
                                          :value="now()->format('Y-m-d')" required />
                        </div>
                        <div class="col-md-6">
                            <x-form-select label="Payment Method" name="method" required
                                           :options="[
                                               'cash' => 'Cash',
                                               'card' => 'Card',
                                               'bank_transfer' => 'Bank Transfer',
                                               'mobile_wallet' => 'Mobile Wallet (JazzCash/Easypaisa)',
                                           ]" />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Transaction ID (Optional)" name="transaction_id"
                                          hint="Card auth code, bank ref number, etc." />
                        </div>
                    </div>
                    <x-form-field label="Notes" name="notes" hint="Internal notes about this payment" />

                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Record Payment</button>
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </x-card>
        </div>

        <div class="col-lg-5">
            <x-card title="Bill Summary">
                <h3 class="mb-3">Room {{ $booking->room->room_number }}</h3>
                <x-info-row label="Total Bill">{{ formatPKR($booking->total_amount) }}</x-info-row>
                <x-info-row label="Already Paid">{{ formatPKR($booking->amount_paid) }}</x-info-row>
                <div class="d-flex justify-content-between pt-2 mt-2" style="border-top: 2px solid var(--color-primary);">
                    <strong>Amount Due</strong>
                    <strong style="color: var(--color-primary); font-size: var(--text-lg);">{{ formatPKR($booking->amount_due) }}</strong>
                </div>
            </x-card>

            @if($booking->allPayments->whereNull('voided_at')->isNotEmpty())
                <x-card title="Previous Payments">
                    @foreach($booking->allPayments->whereNull('voided_at') as $p)
                        <div class="d-flex justify-content-between py-2" style="border-bottom: 1px solid var(--color-border-light);">
                            <div>
                                <small><strong>{{ $p->payment_reference }}</strong></small>
                                <div><small class="text-secondary-custom">{{ formatDate($p->payment_date) }} — {{ $p->method_label }}</small></div>
                            </div>
                            <strong>{{ $p->type === 'refund' ? '-' : '' }}{{ formatPKR($p->amount) }}</strong>
                        </div>
                    @endforeach
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>
