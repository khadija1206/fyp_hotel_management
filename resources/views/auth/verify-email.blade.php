<x-guest-layout>
    <h2 class="auth-form-title">Verify Your Email</h2>

    <p class="text-secondary-custom text-center mb-4">
        Thanks for signing up! Please verify your email by clicking the link we just sent. If you didn't receive it, we'll send another.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-3">
            A new verification link has been sent to your email.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-primary w-100">Resend Verification Email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link w-100 text-secondary-custom">Logout</button>
    </form>
</x-guest-layout>
