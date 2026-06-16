<x-guest-portal-layout title="My Portal">
    <x-page-header :title="'Welcome back, ' . $guest->first_name" subtitle="Your stay information at a glance" />

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <x-stat-card label="Total Stays" :value="$stats['total_stays']" icon="moon-stars" />
        </div>
        <div class="col-md-4">
            <x-stat-card label="Active Bookings" :value="$stats['active_bookings']" icon="calendar-check" accent />
        </div>
        <div class="col-md-4">
            <x-stat-card label="Lifetime Spent" :value="formatPKR($stats['total_spent'])" icon="cash-stack" />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            @if($currentBooking)
                <x-card title="Your Current Stay">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h3 class="mb-1">Room {{ $currentBooking->room->room_number }}</h3>
                            <span class="text-secondary-custom">{{ $currentBooking->room->roomType->name }}, Floor {{ $currentBooking->room->floor }}</span>
                        </div>
                        <x-status-badge status="Checked In" type="success" />
                    </div>

                    <x-info-row label="Check-In">{{ formatDateTime($currentBooking->actual_check_in_at) }}</x-info-row>
                    <x-info-row label="Check-Out">{{ formatDate($currentBooking->check_out_date) }}</x-info-row>
                    <x-info-row label="Booking Reference">{{ $currentBooking->booking_reference }}</x-info-row>
                    <x-info-row label="Total Bill">{{ formatPKR($currentBooking->total_amount) }}</x-info-row>

                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('guest.bookings.show', $currentBooking) }}" class="btn btn-primary">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                        <a href="{{ route('guest.bills.show', $currentBooking) }}" class="btn btn-secondary">
                            <i class="bi bi-receipt"></i> View Bill
                        </a>
                    </div>
                </x-card>
            @else
                <x-card title="No Active Stay">
                    <x-empty-state
                        icon="calendar-event"
                        title="You're not currently checked in"
                        message="When you check in to your next stay, your room details will appear here."
                    />
                </x-card>
            @endif

            <x-card title="Upcoming Bookings">
                @if($upcomingBookings->isEmpty())
                    <p class="text-secondary-custom mb-0">No upcoming bookings.</p>
                @else
                    @foreach($upcomingBookings as $b)
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid var(--color-border-light);">
                            <div>
                                <strong>Room {{ $b->room->room_number }}</strong>
                                <span class="text-secondary-custom"> — {{ $b->room->roomType->name }}</span>
                                <div><small class="text-secondary-custom">{{ formatDate($b->check_in_date) }} → {{ formatDate($b->check_out_date) }}</small></div>
                            </div>
                            <a href="{{ route('guest.bookings.show', $b) }}" class="btn btn-link btn-sm">View</a>
                        </div>
                    @endforeach
                @endif
            </x-card>
        </div>

        <div class="col-lg-5">
            <x-card title="Quick Actions">
                <div class="d-grid gap-2">
                    <a href="{{ route('guest.bookings.index') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-calendar-check me-2"></i> My Bookings
                    </a>
                    <a href="{{ route('guest.bills.index') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-receipt me-2"></i> View Bills
                    </a>
                    <a href="{{ route('guest.complaints.index') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-chat-square-text me-2"></i> Submit Complaint
                    </a>
                    <a href="{{ route('guest.profile.edit') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-person me-2"></i> Update Profile
                    </a>
                </div>
            </x-card>

            <x-card title="Need Help?" subtitle="We're here for you 24/7">
                <p class="text-secondary-custom mb-3">For any assistance during your stay, contact the front desk.</p>
                <x-info-row label="Phone">{{ \App\Models\Setting::get('hotel_phone') }}</x-info-row>
                <x-info-row label="Email">{{ \App\Models\Setting::get('hotel_email') }}</x-info-row>
            </x-card>
        </div>
    </div>
</x-guest-portal-layout>
