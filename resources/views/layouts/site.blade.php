<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <x-seo-meta :seoable="$seoable ?? null" :title="$title ?? null" :description="$description ?? null" />

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/sass/public.scss', 'resources/js/public.js'])

        @if ($gtmId = \App\Models\Setting::get('google_tag_manager_id'))
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $gtmId }}');</script>
        @endif

        @if ($gaId = \App\Models\Setting::get('google_analytics_id'))
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
            <script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '{{ $gaId }}');</script>
        @endif

        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => \App\Models\Setting::get('site_name', config('app.name')),
            'url' => url('/'),
            'logo' => \App\Models\Setting::get('logo_dark', \App\Models\Setting::get('logo')) ? \Illuminate\Support\Facades\Storage::disk('public')->url(\App\Models\Setting::get('logo_dark', \App\Models\Setting::get('logo'))) : null,
            'telephone' => \App\Models\Setting::get('phone'),
            'email' => \App\Models\Setting::get('email'),
            'address' => \App\Models\Setting::get('address'),
        ]) !!}
        </script>
    </head>
    <body class="site">
        @if ($gtmId ?? null)
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        @endif

        @include('layouts.partials.site-header')

        <main>
            {{ $slot }}
        </main>

        @include('layouts.partials.site-footer')

        @livewireScripts
    </body>
</html>
