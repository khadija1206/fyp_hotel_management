<x-app-layout pageTitle="{{ $booking->booking_reference }}">
    <x-breadcrumb :items="[
        'Bookings' => route('bookings.index'),
        $booking->booking_reference => null,
    ]" />

    <x-page-header :title="'Booking ' . $booking->booking_reference"
                   :subtitle="$booking->guest->full_name . ' — Room ' . $booking->room->room_number">
        <x-slot:actions>
            @if($booking->canBeCheckedIn())
                <form method="POST" action="{{ route('check-in.process', $booking) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-success"><i class="bi bi-box-arrow-in-right"></i> Check-In</button>
                </form>
            @endif

            @if($booking->canBeCheckedOut())
                <a href="{{ route('check-out.show', $booking) }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-right"></i> Check-Out
                </a>
            @endif

            @if($booking->canBeCancelled())
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
            @endif
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3">
        <div class="col-lg-8">
            <x-card title="Booking Details">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <x-status-badge :status="$booking->status_label" :type="$booking->status_color" />
                    @if($booking->is_walk_in)<x-status-badge status="Walk-In" type="info" />@endif
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-info-row label="Check-In Date">{{ formatDate($booking->check_in_date) }}</x-info-row>
                        <x-info-row label="Check-Out Date">{{ formatDate($booking->check_out_date) }}</x-info-row>
                        <x-info-row label="Nights">{{ $booking->num_nights }}</x-info-row>
                        <x-info-row label="Number of Guests">{{ $booking->num_guests }}</x-info-row>
                    </div>
                    <div class="col-md-6">
                        @if($booking->actual_check_in_at)
                            <x-info-row label="Actual Check-In">{{ formatDateTime($booking->actual_check_in_at) }}</x-info-row>
                        @endif
                        @if($booking->actual_check_out_at)
                            <x-info-row label="Actual Check-Out">{{ formatDateTime($booking->actual_check_out_at) }}</x-info-row>
                        @endif
                        @if($booking->cancelled_at)
                            <x-info-row label="Cancelled At">{{ formatDateTime($booking->cancelled_at) }}</x-info-row>
                            <x-info-row label="Reason">{{ $booking->cancellation_reason ?? '—' }}</x-info-row>
                        @endif
                        <x-info-row label="Created By">{{ $booking->createdBy?->name ?? 'System' }}</x-info-row>
                    </div>
                </div>

                @if($booking->notes)
                    <h4 class="mt-3">Notes</h4>
                    <p>{{ $booking->notes }}</p>
                @endif
            </x-card>

            <x-card title="Bill Summary">
                <x-info-row label="Rate per Night">{{ formatPKR($booking->rate_per_night) }}</x-info-row>
                <x-info-row label="Subtotal ({{ $booking->num_nights }} nights)">{{ formatPKR($booking->subtotal) }}</x-info-row>
                <x-info-row label="Tax ({{ $booking->tax_rate }}%)">{{ formatPKR($booking->tax_amount) }}</x-info-row>
                <div class="d-flex justify-content-between pt-3" style="border-top: 2px solid var(--color-primary);">
                    <strong style="font-size: var(--text-lg);">Total Amount</strong>
                    <strong style="font-size: var(--text-lg); color: var(--color-primary);">{{ formatPKR($booking->total_amount) }}</strong>
                </div>
                <div class="mt-3">
                    Payment Status:
                    @if($booking->payment_status === 'paid')
                        <x-status-badge status="Paid" type="success" />
                    @elseif($booking->payment_status === 'partial')
                        <x-status-badge status="Partial" type="warning" />
                    @else
                        <x-status-badge status="Unpaid" type="danger" />
                    @endif
                    <small class="text-secondary-custom ms-2">(Payment recording comes in billing module)</small>
                </div>
            </x-card>
        </div>

        <div class="col-lg-4">
            <x-card title="Guest">
                <h4 class="mb-2"><a href="{{ route('guests.show', $booking->guest) }}">{{ $booking->guest->full_name }}</a></h4>
                <x-info-row label="Phone">{{ $booking->guest->phone }}</x-info-row>
                <x-info-row label="Identity">{{ $booking->guest->identity_document }}</x-info-row>
                <x-info-row label="Nationality">{{ $booking->guest->nationality }}</x-info-row>
            </x-card>

            <x-card title="Room">
                <h4 class="mb-2">Room {{ $booking->room->room_number }}</h4>
                <x-info-row label="Type">{{ $booking->room->roomType->name }}</x-info-row>
                <x-info-row label="Floor">{{ $booking->room->floor }}</x-info-row>
                <x-info-row label="Bed">{{ ucfirst($booking->room->roomType->bed_layout) }}</x-info-row>
                <x-info-row label="Capacity">{{ $booking->room->roomType->capacity }}</x-info-row>
            </x-card>

            @if(in_array($booking->status, ['confirmed', 'checked_in']))
                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-secondary w-100">
                    <i class="bi bi-pencil"></i> Modify Booking
                </a>
            @endif
        </div>
    </div>

    @if($booking->canBeCancelled())
        <div class="modal fade" id="cancel-modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Cancel Booking?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to cancel booking <strong>{{ $booking->booking_reference }}</strong>?</p>
                            <x-form-field label="Reason (optional)" name="cancellation_reason" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Booking</button>
                            <button type="submit" class="btn btn-danger">Yes, Cancel Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
