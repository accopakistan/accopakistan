<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <script>
            document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('acco-theme') ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
        </script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex align-items-center justify-content-center min-vh-100 bg-body-tertiary py-5">
        <div class="w-100 px-3" style="max-width: 26rem;">
            <div class="text-center mb-4">
                <a href="/" class="d-inline-block text-primary">
                    <x-application-logo style="width: 3.25rem; height: 3.25rem; fill: currentColor;" />
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
