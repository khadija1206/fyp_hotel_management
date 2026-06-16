<x-guest-portal-layout title="{{ $booking->booking_reference }}">
    <x-breadcrumb :items="[
        'My Bookings' => route('guest.bookings.index'),
        $booking->booking_reference => null,
    ]" />

    <x-page-header :title="'Booking ' . $booking->booking_reference">
        <x-slot:actions>
            <a href="{{ route('guest.bills.show', $booking) }}" class="btn btn-primary">
                <i class="bi bi-receipt"></i> View Bill
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3">
        <div class="col-lg-8">
            <x-card title="Booking Details">
                <div class="mb-3">
                    <x-status-badge :status="$booking->status_label" :type="$booking->status_color" />
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-info-row label="Check-In">{{ formatDate($booking->check_in_date) }}</x-info-row>
                        <x-info-row label="Check-Out">{{ formatDate($booking->check_out_date) }}</x-info-row>
                        <x-info-row label="Nights">{{ $booking->num_nights }}</x-info-row>
                        <x-info-row label="Guests">{{ $booking->num_guests }}</x-info-row>
                    </div>
                    <div class="col-md-6">
                        @if($booking->actual_check_in_at)
                            <x-info-row label="Checked In At">{{ formatDateTime($booking->actual_check_in_at) }}</x-info-row>
                        @endif
                        @if($booking->actual_check_out_at)
                            <x-info-row label="Checked Out At">{{ formatDateTime($booking->actual_check_out_at) }}</x-info-row>
                        @endif
                        <x-info-row label="Booked On">{{ formatDate($booking->created_at) }}</x-info-row>
                    </div>
                </div>

                @if($booking->notes)
                    <h4 class="mt-3">Notes</h4>
                    <p class="mb-0">{{ $booking->notes }}</p>
                @endif

                @if($booking->cancelled_at)
                    <div class="alert alert-warning mt-3 mb-0">
                        <strong>This booking was cancelled</strong> on {{ formatDate($booking->cancelled_at) }}
                        @if($booking->cancellation_reason)<br>Reason: {{ $booking->cancellation_reason }}@endif
                    </div>
                @endif
            </x-card>
        </div>

        <div class="col-lg-4">
            <x-card title="Room">
                <h3 class="mb-2">Room {{ $booking->room->room_number }}</h3>
                <x-info-row label="Type">{{ $booking->room->roomType->name }}</x-info-row>
                <x-info-row label="Floor">{{ $booking->room->floor }}</x-info-row>
                <x-info-row label="Capacity">{{ $booking->room->roomType->capacity }} guests</x-info-row>

                @if($booking->room->roomType->amenities)
                    <h5 class="mt-3">Amenities</h5>
                    <div>
                        @foreach($booking->room->roomType->amenities_array as $amenity)
                            <span class="badge bg-subtle text-dark me-1 mb-1">{{ $amenity }}</span>
                        @endforeach
                    </div>
                @endif
            </x-card>

            <x-card title="Bill Summary">
                <x-info-row label="Rate">{{ formatPKR($booking->rate_per_night) }}/night</x-info-row>
                <x-info-row label="Subtotal">{{ formatPKR($booking->subtotal) }}</x-info-row>
                <x-info-row label="Tax">{{ formatPKR($booking->tax_amount) }}</x-info-row>
                <div class="d-flex justify-content-between pt-2 mt-2" style="border-top: 2px solid var(--color-primary);">
                    <strong>Total</strong>
                    <strong style="color: var(--color-primary);">{{ formatPKR($booking->total_amount) }}</strong>
                </div>

                <div class="mt-3 text-center">
                    @if($booking->payment_status === 'paid')
                        <x-status-badge status="Paid" type="success" />
                    @elseif($booking->payment_status === 'partial')
                        <x-status-badge status="Partial" type="warning" />
                    @else
                        <x-status-badge status="Unpaid" type="danger" />
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</x-guest-portal-layout>
