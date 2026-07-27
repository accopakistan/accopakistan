<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
@if ($metaKeywords)
    <meta name="keywords" content="{{ $metaKeywords }}">
@endif
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta name="robots" content="{{ $seoable?->seo?->robots ?? 'index,follow' }}">

<meta property="og:site_name" content="{{ \App\Models\Setting::get('site_name', config('app.name')) }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:type" content="website">
@if ($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
@endif

<meta name="twitter:card" content="{{ $seoable?->seo?->twitter_card ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
@if ($ogImage)
    <meta name="twitter:image" content="{{ $ogImage }}">
@endif

@if ($schemaJson)
    <script type="application/ld+json">{!! json_encode($schemaJson) !!}</script>
@endif
