<x-app-layout pageTitle="Issue Refund">
    <x-breadcrumb :items="[
        'Bookings' => route('bookings.index'),
        $booking->booking_reference => route('bookings.show', $booking),
        'Issue Refund' => null,
    ]" />

    <x-page-header title="Issue Refund" :subtitle="$booking->booking_reference" />

    <div class="row g-3">
        <div class="col-lg-7">
            <x-card title="Refund Details">
                <form method="POST" action="{{ route('refunds.store', $booking) }}">
                    @csrf

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Maximum refundable: <strong>{{ formatPKR($booking->amount_paid) }}</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-6"><x-form-field label="Refund Amount" name="amount" type="number" step="0.01" required /></div>
                        <div class="col-md-6"><x-form-field label="Date" name="payment_date" type="date" :value="now()->format('Y-m-d')" required /></div>
                        <div class="col-12">
                            <x-form-select label="Refund Method" name="method" required
                                           :options="['cash' => 'Cash', 'card' => 'Card', 'bank_transfer' => 'Bank Transfer', 'mobile_wallet' => 'Mobile Wallet']" />
                        </div>
                        <div class="col-12">
                            <x-form-field label="Reason for Refund" name="notes" required hint="This will be recorded permanently" />
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-danger"><i class="bi bi-arrow-return-left"></i> Issue Refund</button>
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </x-card>
        </div>

        <div class="col-lg-5">
            <x-card title="Booking">
                <x-info-row label="Guest">{{ $booking->guest->full_name }}</x-info-row>
                <x-info-row label="Room">Room {{ $booking->room->room_number }}</x-info-row>
                <x-info-row label="Status">{{ $booking->status_label }}</x-info-row>
                <x-info-row label="Total Bill">{{ formatPKR($booking->total_amount) }}</x-info-row>
                <x-info-row label="Total Paid">{{ formatPKR($booking->amount_paid) }}</x-info-row>
            </x-card>
        </div>
    </div>
</x-app-layout>
