<x-app-layout pageTitle="Walk-In Registration">
    <x-breadcrumb :items="[
        'Dashboard' => route('dashboard'),
        'Walk-In' => null,
    ]" />

    <x-page-header title="Walk-In Registration" subtitle="Fast track: register guest, create booking, and check-in in one step" />

    <form method="POST" action="{{ route('walk-in.store') }}">
        @csrf

        <x-card title="Guest Information">
            <div class="row">
                <div class="col-md-6"><x-form-field label="First Name" name="first_name" required /></div>
                <div class="col-md-6"><x-form-field label="Last Name" name="last_name" required /></div>
                <div class="col-md-6"><x-form-field label="Phone" name="phone" required /></div>
                <div class="col-md-6"><x-form-field label="Email" name="email" type="email" /></div>
                <div class="col-md-6"><x-form-field label="CNIC" name="cnic" placeholder="35202-1234567-1" /></div>
                <div class="col-md-6"><x-form-field label="Passport Number" name="passport_number" /></div>
                <div class="col-md-6"><x-form-field label="Nationality" name="nationality" :value="'Pakistani'" required /></div>
                <div class="col-12"><x-form-field label="Address" name="address" /></div>
            </div>
        </x-card>

        <x-card title="Booking Details">
            <div class="row">
                <div class="col-md-6">
                    <x-form-field label="Check-Out Date" name="check_out_date" type="date" required hint="Check-in is today" />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Number of Guests" name="num_guests" type="number" :value="1" required />
                </div>
            </div>
            <x-form-field label="Notes" name="notes" />
        </x-card>

        <x-card title="Select Room">
            @if($availableRooms->isEmpty())
                <div class="alert alert-warning">No rooms are currently available.</div>
            @else
                <div class="row g-2">
                    @foreach($availableRooms as $room)
                        <div class="col-md-4">
                            <label class="d-block p-3 border rounded" style="cursor:pointer;">
                                <input type="radio" name="room_id" value="{{ $room->id }}" required class="me-2">
                                <strong>Room {{ $room->room_number }}</strong> <small class="text-secondary-custom">Floor {{ $room->floor }}</small>
                                <div>{{ $room->roomType->name }} ({{ $room->roomType->capacity }} guests)</div>
                                <div class="fw-bold text-primary">{{ formatPKR($room->price_per_night) }}<small>/night</small></div>
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card>

        <div class="d-flex gap-2">
            <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Register & Check-In</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</x-app-layout>
