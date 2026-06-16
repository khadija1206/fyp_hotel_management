<x-app-layout pageTitle="System Settings">
    <x-page-header title="System Settings" subtitle="Hotel-wide configuration" />

    <x-card title="Hotel Information">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <x-form-field label="Hotel Name" name="hotel_name" :value="$settings['hotel_name']" required />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Email" name="hotel_email" type="email" :value="$settings['hotel_email']" required />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Phone" name="hotel_phone" :value="$settings['hotel_phone']" required />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Currency Symbol" name="currency_symbol" :value="$settings['currency_symbol']" required />
                </div>
                <div class="col-12">
                    <x-form-field label="Address" name="hotel_address" :value="$settings['hotel_address']" required />
                </div>
            </div>

            <h3 class="mt-4 mb-3">Billing & Operations</h3>

            <div class="row">
                <div class="col-md-4">
                    <x-form-field label="Tax Rate (%)" name="tax_rate" type="number" :value="$settings['tax_rate']" required hint="GST rate applied to bookings" />
                </div>
                <div class="col-md-4">
                    <x-form-field label="Default Check-In Time" name="default_check_in_time" type="time" :value="$settings['default_check_in_time']" required />
                </div>
                <div class="col-md-4">
                    <x-form-field label="Default Check-Out Time" name="default_check_out_time" type="time" :value="$settings['default_check_out_time']" required />
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Settings</button>
            </div>
        </form>
    </x-card>
</x-app-layout>
