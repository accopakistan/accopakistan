<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? __('Admin') }} &middot; {{ config('app.name') }}</title>

        <script>
            document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('acco-theme') ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
        </script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>
        <div class="admin-wrapper">
            @include('admin.layouts.partials.sidebar')

            <div class="admin-content d-flex flex-column {{ $fullHeight ? 'admin-content--full-height' : '' }}">
                @include('admin.layouts.partials.topbar')

                <main class="flex-grow-1 {{ $fullHeight ? 'admin-main--full-height' : 'p-3 p-lg-4' }}">
                    @isset($header)
                        <div class="mb-4">
                            {{ $header }}
                        </div>
                    @endisset

                    @if (session('status'))
                        <div class="alert alert-success {{ $fullHeight ? 'm-3 mb-0' : '' }}">{{ session('status') }}</div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
