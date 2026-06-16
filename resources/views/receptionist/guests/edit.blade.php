<x-app-layout pageTitle="Edit Guest">
    <x-breadcrumb :items="[
        'Guests' => route('guests.index'),
        $guest->full_name => route('guests.show', $guest),
        'Edit' => null,
    ]" />

    <x-page-header title="Edit Guest" :subtitle="$guest->full_name" />

    <form method="POST" action="{{ route('guests.update', $guest) }}">
        @csrf
        @method('PUT')

        <x-card title="Personal Information">
            <div class="row">
                <div class="col-md-6"><x-form-field label="First Name" name="first_name" :value="$guest->first_name" required /></div>
                <div class="col-md-6"><x-form-field label="Last Name" name="last_name" :value="$guest->last_name" required /></div>
                <div class="col-md-6"><x-form-field label="Phone Number" name="phone" :value="$guest->phone" required /></div>
                <div class="col-md-6"><x-form-field label="Email" name="email" type="email" :value="$guest->email" /></div>
                <div class="col-md-4"><x-form-field label="Date of Birth" name="date_of_birth" type="date" :value="$guest->date_of_birth?->format('Y-m-d')" /></div>
                <div class="col-md-4">
                    <x-form-select label="Gender" name="gender" :selected="$guest->gender"
                                   :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" />
                </div>
                <div class="col-md-4"><x-form-field label="Nationality" name="nationality" :value="$guest->nationality" required /></div>
            </div>
        </x-card>

        <x-card title="Identity Document">
            <div class="row">
                <div class="col-md-6"><x-form-field label="CNIC" name="cnic" :value="$guest->cnic" /></div>
                <div class="col-md-6"><x-form-field label="Passport Number" name="passport_number" :value="$guest->passport_number" /></div>
            </div>
        </x-card>

        <x-card title="Address">
            <div class="row">
                <div class="col-12"><x-form-field label="Street Address" name="address" :value="$guest->address" /></div>
                <div class="col-md-6"><x-form-field label="City" name="city" :value="$guest->city" /></div>
                <div class="col-md-6"><x-form-field label="Country" name="country" :value="$guest->country" required /></div>
            </div>
        </x-card>

        <x-card title="Emergency Contact (Optional)">
            <div class="row">
                <div class="col-md-6"><x-form-field label="Contact Name" name="emergency_contact_name" :value="$guest->emergency_contact_name" /></div>
                <div class="col-md-6"><x-form-field label="Contact Phone" name="emergency_contact_phone" :value="$guest->emergency_contact_phone" /></div>
            </div>
            <x-form-field label="Notes" name="notes" :value="$guest->notes" />
        </x-card>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Changes</button>
            <a href="{{ route('guests.show', $guest) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</x-app-layout>
