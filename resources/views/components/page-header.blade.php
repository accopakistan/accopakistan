@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
    'image' => null,
    'breadcrumbs' => [],
])

<section class="page-hero">
    <div class="page-hero__media">
        <img src="{{ $image ?: 'https://picsum.photos/seed/acco-page-default/1920/900' }}" alt="" loading="eager">
    </div>
    <div class="container page-hero__content">
        @if (!empty($breadcrumbs))
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">{{ __('Home') }}</a>
                @foreach ($breadcrumbs as $label => $url)
                    <span>/</span>
                    @if ($url)
                        <a href="{{ $url }}">{{ $label }}</a>
                    @else
                        <span>{{ $label }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        @if ($eyebrow)
            <div class="eyebrow" style="color:var(--c-white);margin-bottom:1rem;">{{ $eyebrow }}</div>
        @endif

        <h1 class="display-1" data-reveal-text><span class="line"><span>{{ $title }}</span></span></h1>

        @isset($subtitle)
            <p class="lede" style="color:rgba(255,255,255,0.82);margin-top:1.25rem;max-width:38rem;">{{ $subtitle }}</p>
        @endisset
    </div>
</section>
