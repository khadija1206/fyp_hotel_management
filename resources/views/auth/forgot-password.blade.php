<x-guest-layout>
    <h2 class="auth-form-title">Forgot Password?</h2>

    <p class="text-secondary-custom small text-center mb-4">
        Enter your email and we'll send you a password reset link.
    </p>

    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <x-form-field
            label="Email Address"
            name="email"
            type="email"
            required
            autofocus
        />

        <button type="submit" class="btn btn-primary w-100 mb-3">
            Send Reset Link
        </button>

        <p class="text-center mb-0 small">
            <a href="{{ route('login') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left"></i> Back to login
            </a>
        </p>
    </form>
</x-guest-layout>
