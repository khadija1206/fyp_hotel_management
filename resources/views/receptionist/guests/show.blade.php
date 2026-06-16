<x-app-layout pageTitle="{{ $guest->full_name }}">
    <x-breadcrumb :items="[
        'Guests' => route('guests.index'),
        $guest->full_name => null,
    ]" />

    <x-page-header :title="$guest->full_name" :subtitle="$guest->identity_document">
        <x-slot:actions>
            <a href="{{ route('bookings.create', ['guest_id' => $guest->id]) }}" class="btn btn-primary">
                <i class="bi bi-calendar-plus"></i> New Booking
            </a>
            <a href="{{ route('guests.edit', $guest) }}" class="btn btn-secondary">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3">
        <div class="col-lg-4">
            <x-card title="Contact">
                <x-info-row label="Phone">{{ $guest->phone }}</x-info-row>
                <x-info-row label="Email">{{ $guest->email ?? '—' }}</x-info-row>
                <x-info-row label="Nationality">{{ $guest->nationality }}</x-info-row>
                <x-info-row label="DOB">{{ $guest->date_of_birth ? formatDate($guest->date_of_birth) : '—' }}</x-info-row>
                <x-info-row label="Gender">{{ $guest->gender ? ucfirst($guest->gender) : '—' }}</x-info-row>
            </x-card>

            <x-card title="Address">
                <p class="mb-0">{{ $guest->address ?? '—' }}<br>{{ $guest->city ?? '' }}{{ $guest->city ? ', ' : '' }}{{ $guest->country }}</p>
            </x-card>

            @if($guest->emergency_contact_name)
                <x-card title="Emergency Contact">
                    <x-info-row label="Name">{{ $guest->emergency_contact_name }}</x-info-row>
                    <x-info-row label="Phone">{{ $guest->emergency_contact_phone }}</x-info-row>
                </x-card>
            @endif
        </div>

        <div class="col-lg-8">
            <x-card title="Booking History" subtitle="All bookings for this guest">
                @if($guest->bookings->isEmpty())
                    <x-empty-state icon="calendar-x" title="No bookings yet" message="This guest has not made any bookings.">
                        <x-slot:action>
                            <a href="{{ route('bookings.create', ['guest_id' => $guest->id]) }}" class="btn btn-primary">Create First Booking</a>
                        </x-slot:action>
                    </x-empty-state>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>Reference</th><th>Room</th><th>Dates</th><th>Total</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @foreach($guest->bookings as $booking)
                                    <tr>
                                        <td><a href="{{ route('bookings.show', $booking) }}"><strong>{{ $booking->booking_reference }}</strong></a></td>
                                        <td>Room {{ $booking->room->room_number }} <small class="text-secondary-custom">({{ $booking->room->roomType->name }})</small></td>
                                        <td>{{ formatDate($booking->check_in_date) }} → {{ formatDate($booking->check_out_date) }}</td>
                                        <td>{{ formatPKR($booking->total_amount) }}</td>
                                        <td><x-status-badge :status="$booking->status_label" :type="$booking->status_color" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
