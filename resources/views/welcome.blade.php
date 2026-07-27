<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <script>
            document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('acco-theme') ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
        </script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex align-items-center justify-content-center min-vh-100 bg-body-tertiary text-center px-3">
        <div>
            <x-application-logo style="width: 4rem; height: 4rem; fill: currentColor;" class="text-primary mb-4" />
            <h1 class="h3 fw-semibold font-heading mb-2">ACCO Pakistan</h1>
            <p class="text-muted mb-4">Architecture, Engineering &amp; Construction — site launching soon.</p>

            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>
            @endauth
        </div>
    </body>
</html>
