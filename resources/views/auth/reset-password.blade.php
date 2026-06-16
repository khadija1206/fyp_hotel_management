<x-guest-layout>
    <h2 class="auth-form-title">Reset Password</h2>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-form-field
            label="Email"
            name="email"
            type="email"
            :value="$request->email"
            required
            autofocus
        />

        <x-form-field
            label="New Password"
            name="password"
            type="password"
            required
            autocomplete="new-password"
        />

        <x-form-field
            label="Confirm New Password"
            name="password_confirmation"
            type="password"
            required
            autocomplete="new-password"
        />

        <button type="submit" class="btn btn-primary w-100">
            Reset Password
        </button>
    </form>
</x-guest-layout>
