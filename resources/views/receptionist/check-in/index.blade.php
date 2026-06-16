<x-app-layout pageTitle="Check-In Queue">
    <x-page-header title="Check-In Queue" subtitle="Confirmed bookings ready for check-in" />

    @if($bookings->isEmpty())
        <x-card>
            <x-empty-state icon="calendar-check" title="No pending check-ins" message="All confirmed bookings have been processed." />
        </x-card>
    @else
        <div class="row g-3">
            @foreach($bookings as $booking)
                <div class="col-lg-6">
                    <x-card>
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <h4 class="mb-1">{{ $booking->guest->full_name }}</h4>
                                <small class="text-secondary-custom">{{ $booking->booking_reference }}</small>
                            </div>
                            <div class="text-end">
                                <strong>Room {{ $booking->room->room_number }}</strong>
                                <div><small class="text-secondary-custom">{{ $booking->room->roomType->name }}</small></div>
                            </div>
                        </div>

                        <x-info-row label="Check-In Expected">{{ formatDate($booking->check_in_date) }}</x-info-row>
                        <x-info-row label="Check-Out">{{ formatDate($booking->check_out_date) }}</x-info-row>
                        <x-info-row label="Nights">{{ $booking->num_nights }}</x-info-row>
                        <x-info-row label="Identity">{{ $booking->guest->identity_document }}</x-info-row>
                        <x-info-row label="Phone">{{ $booking->guest->phone }}</x-info-row>

                        @if($booking->check_in_date->isPast() && !$booking->check_in_date->isToday())
                            <div class="alert alert-warning mt-2 mb-3">
                                <i class="bi bi-exclamation-triangle"></i> Check-in was due on {{ formatDate($booking->check_in_date) }}
                            </div>
                        @endif

                        <div class="d-flex gap-2 mt-3">
                            <form method="POST" action="{{ route('check-in.process', $booking) }}" class="flex-grow-1">
                                @csrf
                                <button class="btn btn-success w-100">
                                    <i class="bi bi-box-arrow-in-right"></i> Check-In Now
                                </button>
                            </form>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary">Details</a>
                        </div>
                    </x-card>
                </div>
            @endforeach
        </div>

        <div class="mt-3">{{ $bookings->links() }}</div>
    @endif
</x-app-layout>
