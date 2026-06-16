<x-guest-layout>
    <h2 class="auth-form-title">Create an account</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <x-form-field
            label="Full Name"
            name="name"
            required
            autofocus
            autocomplete="name"
        />

        <x-form-field
            label="Email Address"
            name="email"
            type="email"
            required
            autocomplete="username"
        />

        <x-form-field
            label="Phone Number"
            name="phone"
            type="tel"
            required
            placeholder="+92-300-1234567"
            hint="We'll use this for booking confirmations"
        />

        <x-form-field
            label="Password"
            name="password"
            type="password"
            required
            autocomplete="new-password"
            hint="Min 8 characters"
        />

        <x-form-field
            label="Confirm Password"
            name="password_confirmation"
            type="password"
            required
            autocomplete="new-password"
        />

        <button type="submit" class="btn btn-primary w-100 mb-3">
            Create Account
        </button>

        <p class="text-center text-secondary-custom mb-0 small">
            Already have an account?
            <a href="{{ route('login') }}" class="text-decoration-none fw-medium">Sign in</a>
        </p>
    </form>
</x-guest-layout>
