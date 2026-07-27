@php
    $featured = $posts->first();
    $rest = $posts->getCollection()->skip(1);
@endphp

<x-site-layout :title="__('Insights')" :description="__('News, insights, and updates from ACCO Pakistan.')">
    <x-page-header
        :eyebrow="__('Insights')"
        :title="__('Our Journal')"
        subtitle="Project stories, industry analysis, and design thinking from ACCO's architects and engineers."
        image="https://picsum.photos/seed/acco-blog-header/1920/900"
        :breadcrumbs="[__('Insights') => null]"
    />

    <section class="section">
        <div class="container">
            @if ($categories->isNotEmpty())
                <div class="filters reveal-up" style="margin-bottom:3rem;">
                    <a href="{{ route('blog.index') }}" class="filter-pill {{ request('category') ? '' : 'is-active' }}">{{ __('All') }}</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="filter-pill {{ request('category') === $category->slug ? 'is-active' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($posts->isEmpty())
                <p class="text-muted">{{ __('Articles will be published here soon.') }}</p>
            @else
                @if ($featured)
                    <a href="{{ route('blog.show', $featured) }}" class="post-feature reveal-up" style="margin-bottom:clamp(3rem,6vw,5rem);">
                        <div class="post-feature__media">
                            @if ($featured->featuredImageUrl())
                                <img src="{{ $featured->featuredImageUrl() }}" alt="{{ $featured->title }}" loading="lazy">
                            @else
                                <img src="https://picsum.photos/seed/acco-blog-{{ $featured->id }}/1200/900" alt="{{ $featured->title }}" loading="lazy">
                            @endif
                        </div>
                        <div>
                            <div class="post-card__meta">
                                @if ($featured->category)<span>{{ $featured->category->name }}</span>@endif
                                <span>{{ $featured->published_at?->format('M j, Y') }}</span>
                                @if ($featured->reading_time)<span>{{ $featured->reading_time }} {{ __('min read') }}</span>@endif
                            </div>
                            <h2 class="display-2">{{ $featured->title }}</h2>
                            <p class="lede" style="margin-top:1rem;">{{ $featured->excerpt }}</p>
                            <span class="btn--ghost" style="display:inline-flex;align-items:center;gap:0.4rem;margin-top:1.5rem;">
                                {{ __('Read Article') }} <x-icon name="arrow-right" />
                            </span>
                        </div>
                    </a>
                @endif

                <div class="grid">
                    @foreach ($rest as $post)
                        <a href="{{ route('blog.show', $post) }}" class="post-card col-md-4 reveal-up">
                            <div class="post-card__media">
                                @if ($post->featuredImageUrl())
                                    <img src="{{ $post->featuredImageUrl() }}" alt="{{ $post->title }}" loading="lazy">
                                @else
                                    <img src="https://picsum.photos/seed/acco-blog-{{ $post->id }}/700/440" alt="{{ $post->title }}" loading="lazy">
                                @endif
                            </div>
                            <div class="post-card__meta">
                                @if ($post->category)<span>{{ $post->category->name }}</span>@endif
                                <span>{{ $post->published_at?->format('M j, Y') }}</span>
                            </div>
                            <h3 class="post-card__title">{{ $post->title }}</h3>
                        </a>
                    @endforeach
                </div>

                <div style="margin-top:3rem;">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
</x-site-layout>
