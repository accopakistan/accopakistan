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
    <body class="bg-body-tertiary">
        @include('layouts.navigation')

        @isset($header)
            <div class="bg-body border-bottom py-4 mb-4">
                <div class="container">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <main class="container pb-5">
            {{ $slot }}
        </main>

        @livewireScripts
    </body>
</html>
