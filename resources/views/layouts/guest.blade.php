<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Welcome' }} — {{ config('app.name', 'Hotel Management System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700|jetbrains-mono:400,500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-brand">
                <div class="auth-brand-icon">
                    <i class="bi bi-building"></i>
                </div>
                <h1 class="auth-brand-title">{{ config('app.name', 'Hotel Management System') }}</h1>
                <p class="auth-brand-subtitle">Streamlined hotel operations</p>
            </div>

            {{ $slot }}
        </div>
    </div>
</body>
</html>
