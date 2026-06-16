<x-guest-layout>
    <h2 class="auth-form-title">Welcome back</h2>

    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-form-field
            label="Email Address"
            name="email"
            type="email"
            required
            autofocus
            autocomplete="username"
        />

        <x-form-field
            label="Password"
            name="password"
            type="password"
            required
            autocomplete="current-password"
        />

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">
            Sign In
        </button>

        <p class="text-center text-secondary-custom mb-0 small">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-decoration-none fw-medium">Register</a>
        </p>
    </form>
</x-guest-layout>
