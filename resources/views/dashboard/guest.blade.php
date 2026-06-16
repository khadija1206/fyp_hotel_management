<x-guest-portal-layout title="My Portal">
    <x-page-header title="Welcome, {{ Auth::user()->name }}" subtitle="Your stay information" />

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <x-card title="My Current Booking">
                <x-empty-state
                    icon="calendar-event"
                    title="No active booking"
                    message="When you check in, your booking details will appear here."
                />
            </x-card>
        </div>
        <div class="col-md-6">
            <x-card title="Recent Activity">
                <x-empty-state
                    icon="clock-history"
                    title="No activity"
                    message="Your booking history and complaints will appear here."
                />
            </x-card>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <x-card title="Quick Actions">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary text-start">
                        <i class="bi bi-chat-square-text"></i> Submit a Complaint
                    </a>
                    <a href="#" class="btn btn-outline-primary text-start">
                        <i class="bi bi-receipt"></i> View My Bills
                    </a>
                    <a href="#" class="btn btn-outline-primary text-start">
                        <i class="bi bi-clock-history"></i> Booking History
                    </a>
                </div>
            </x-card>
        </div>
        <div class="col-md-6">
            <x-card title="Need Help?">
                <p class="text-secondary-custom mb-3">Contact the front desk for any assistance during your stay.</p>
                <div class="d-flex gap-2">
                    <a href="tel:+92" class="btn btn-primary"><i class="bi bi-telephone"></i> Call Reception</a>
                    <a href="#" class="btn btn-secondary"><i class="bi bi-envelope"></i> Send Message</a>
                </div>
            </x-card>
        </div>
    </div>
</x-guest-portal-layout>
