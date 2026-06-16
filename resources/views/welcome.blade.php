<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex flex-column">
        @if (Route::has('login'))
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container">
                    <span class="navbar-brand fw-bold mb-0">
                        <i class="bi bi-building"></i> {{ config('app.name') }}
                    </span>
                    <div class="ms-auto d-flex gap-2">
                        @auth
                            <a class="btn btn-outline-primary btn-sm" href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a>
                        @else
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">{{ __('Log in') }}</a>
                            @if (Route::has('register'))
                                <a class="btn btn-primary btn-sm" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </nav>
        @endif

        <main class="flex-grow-1 d-flex align-items-center">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h1 class="display-6 fw-bold mb-3">{{ config('app.name') }}</h1>
                        <p class="lead text-muted mb-4">
                            {{ __('Hotel Management System — Laravel :version (PHP :php). Setup complete.', ['version' => Illuminate\Foundation\Application::VERSION, 'php' => PHP_VERSION]) }}
                        </p>
                        @guest
                            @if (Route::has('login'))
                                <a class="btn btn-primary btn-lg me-2" href="{{ route('login') }}">{{ __('Log in') }}</a>
                            @endif
                            @if (Route::has('register'))
                                <a class="btn btn-outline-secondary btn-lg" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        @else
                            <a class="btn btn-primary btn-lg" href="{{ url('/dashboard') }}">{{ __('Go to dashboard') }}</a>
                        @endguest
                    </div>
                </div>
            </div>
        </main>

        <footer class="border-top bg-white py-3 mt-auto">
            <div class="container small text-muted text-center">
                Laravel {{ Illuminate\Foundation\Application::VERSION }} · PHP {{ PHP_VERSION }}
            </div>
        </footer>
    </div>
</body>
</html>
