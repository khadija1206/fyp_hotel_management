<x-app-layout pageTitle="Modify Booking">
    <x-breadcrumb :items="[
        'Bookings' => route('bookings.index'),
        $booking->booking_reference => route('bookings.show', $booking),
        'Modify' => null,
    ]" />

    <x-page-header :title="'Modify Booking ' . $booking->booking_reference"
                   subtitle="Only number of guests and notes can be modified. To change dates or room, cancel and create a new booking." />

    <x-card>
        <form method="POST" action="{{ route('bookings.update', $booking) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4">
                    <x-form-field label="Number of Guests" name="num_guests" type="number" :value="$booking->num_guests" required />
                </div>
            </div>
            <x-form-field label="Notes" name="notes" :value="$booking->notes" />

            <div class="d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Changes</button>
                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
