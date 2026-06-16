<x-guest-portal-layout title="Submit Complaint">
    <x-breadcrumb :items="[
        'My Complaints' => route('guest.complaints.index'),
        'Submit' => null,
    ]" />

    <x-page-header title="Submit a Complaint" subtitle="Tell us what went wrong, we'll make it right." />

    <form method="POST" action="{{ route('guest.complaints.store') }}">
        @csrf

        <x-card>
            <x-form-field label="Title" name="title" required placeholder="Brief summary of the issue" />

            <x-form-select label="Category" name="category" required
                           :options="[
                               'room' => 'Room (AC, TV, furniture, etc.)',
                               'food' => 'Food & Beverage',
                               'service' => 'Service Quality',
                               'billing' => 'Billing / Pricing',
                               'noise' => 'Noise Issue',
                               'cleanliness' => 'Cleanliness',
                               'other' => 'Other',
                           ]" />

            @if($bookings->isNotEmpty())
                <x-form-select label="Related Booking (Optional)" name="booking_id"
                               placeholder="No specific booking"
                               :options="$bookings->mapWithKeys(fn($b) => [
                                   $b->id => $b->booking_reference . ' — Room ' . $b->room->room_number . ' (' . formatDate($b->check_in_date) . ')'
                               ])->toArray()" />
            @endif

            <div class="mb-3">
                <label class="form-label">Detailed Description <span class="text-danger">*</span></label>
                <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror" required maxlength="2000" placeholder="Please describe the issue in detail. Include when it happened, what you experienced, and any other relevant information.">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="form-text text-secondary-custom">The more details you provide, the faster we can resolve this.</small>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>What happens next?</strong> Our team will review your complaint, assign it to the appropriate staff member, and work to resolve it as quickly as possible. You'll be able to track its status here.
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Submit Complaint</button>
                <a href="{{ route('guest.complaints.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </x-card>
    </form>
</x-guest-portal-layout>
