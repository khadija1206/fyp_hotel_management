<x-app-layout pageTitle="Check-Out">
    <x-breadcrumb :items="[
        'Check-Out' => route('check-out.index'),
        $booking->booking_reference => null,
    ]" />

    <x-page-header :title="'Check-Out: ' . $booking->guest->full_name"
                   :subtitle="'Room ' . $booking->room->room_number . ' — ' . $booking->booking_reference" />

    <div class="row g-3">
        <div class="col-lg-7">
            <x-card title="Final Bill">
                <table class="table">
                    <tr>
                        <td>Room {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</td>
                        <td class="text-end">{{ formatPKR($booking->rate_per_night) }}/night</td>
                    </tr>
                    <tr>
                        <td>{{ $booking->num_nights }} night(s)</td>
                        <td class="text-end">{{ formatPKR($booking->subtotal) }}</td>
                    </tr>
                    <tr>
                        <td>Tax ({{ $booking->tax_rate }}%)</td>
                        <td class="text-end">{{ formatPKR($booking->tax_amount) }}</td>
                    </tr>
                    <tr style="border-top: 2px solid var(--color-primary);">
                        <td><strong>Total</strong></td>
                        <td class="text-end"><strong style="color: var(--color-primary); font-size: var(--text-lg);">{{ formatPKR($booking->total_amount) }}</strong></td>
                    </tr>
                </table>

                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle"></i> Payment recording will be added in the Billing module. For now, confirm with the guest that payment has been arranged.
                </div>
            </x-card>

            <x-card title="Stay Summary">
                <x-info-row label="Booked On">{{ formatDate($booking->created_at) }}</x-info-row>
                <x-info-row label="Checked In">{{ formatDateTime($booking->actual_check_in_at) }}</x-info-row>
                <x-info-row label="Expected Check-Out">{{ formatDate($booking->check_out_date) }}</x-info-row>
                <x-info-row label="Number of Guests">{{ $booking->num_guests }}</x-info-row>
                @if($booking->notes)
                    <x-info-row label="Notes">{{ $booking->notes }}</x-info-row>
                @endif
            </x-card>
        </div>

        <div class="col-lg-5">
            <x-card title="Confirm Check-Out">
                <p>You are about to check out:</p>
                <ul>
                    <li><strong>{{ $booking->guest->full_name }}</strong></li>
                    <li>Room {{ $booking->room->room_number }}</li>
                    <li>Total: {{ formatPKR($booking->total_amount) }}</li>
                </ul>

                <p class="text-secondary-custom">After check-out, the room will be marked as available.</p>

                <form method="POST" action="{{ route('check-out.process', $booking) }}">
                    @csrf
                    <button class="btn btn-success w-100 mb-2">
                        <i class="bi bi-check2-circle"></i> Confirm Check-Out
                    </button>
                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary w-100">Cancel</a>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
