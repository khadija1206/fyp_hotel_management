<x-guest-layout>
    <h2 class="auth-form-title">Confirm Password</h2>

    <p class="text-secondary-custom small text-center mb-4">
        Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <x-form-field
            label="Password"
            name="password"
            type="password"
            required
            autocomplete="current-password"
        />

        <button type="submit" class="btn btn-primary w-100">Confirm</button>
    </form>
</x-guest-layout>
