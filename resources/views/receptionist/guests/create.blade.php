<x-app-layout pageTitle="Register Guest">
    <x-breadcrumb :items="[
        'Guests' => route('guests.index'),
        'Register Guest' => null,
    ]" />

    <x-page-header title="Register New Guest" />

    <form method="POST" action="{{ route('guests.store') }}">
        @csrf

        <x-card title="Personal Information">
            <div class="row">
                <div class="col-md-6"><x-form-field label="First Name" name="first_name" required /></div>
                <div class="col-md-6"><x-form-field label="Last Name" name="last_name" required /></div>
                <div class="col-md-6"><x-form-field label="Phone Number" name="phone" required placeholder="+92-300-1234567" /></div>
                <div class="col-md-6"><x-form-field label="Email" name="email" type="email" /></div>
                <div class="col-md-4"><x-form-field label="Date of Birth" name="date_of_birth" type="date" /></div>
                <div class="col-md-4">
                    <x-form-select label="Gender" name="gender"
                                   :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" />
                </div>
                <div class="col-md-4"><x-form-field label="Nationality" name="nationality" :value="'Pakistani'" required /></div>
            </div>
        </x-card>

        <x-card title="Identity Document" subtitle="Either CNIC or Passport is required">
            <div class="row">
                <div class="col-md-6"><x-form-field label="CNIC" name="cnic" placeholder="35202-1234567-1" hint="Pakistani National ID Card" /></div>
                <div class="col-md-6"><x-form-field label="Passport Number" name="passport_number" hint="For foreign nationals" /></div>
            </div>
        </x-card>

        <x-card title="Address">
            <div class="row">
                <div class="col-12"><x-form-field label="Street Address" name="address" /></div>
                <div class="col-md-6"><x-form-field label="City" name="city" /></div>
                <div class="col-md-6"><x-form-field label="Country" name="country" :value="'Pakistan'" required /></div>
            </div>
        </x-card>

        <x-card title="Emergency Contact (Optional)">
            <div class="row">
                <div class="col-md-6"><x-form-field label="Contact Name" name="emergency_contact_name" /></div>
                <div class="col-md-6"><x-form-field label="Contact Phone" name="emergency_contact_phone" /></div>
            </div>
            <x-form-field label="Notes" name="notes" hint="Any special requirements or remarks" />
        </x-card>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Register Guest</button>
            <a href="{{ route('guests.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</x-app-layout>
