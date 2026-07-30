@php
    $rawContent = $post->content ?? '';
    $isHtml = strip_tags($rawContent) !== $rawContent;

    $paragraphs = collect();
    $headings = collect();
    $renderedContent = $rawContent;

    if ($isHtml) {
        $headingIndex = 0;
        $renderedContent = preg_replace_callback('/<(h1|h2)([^>]*)>(.*?)<\/\1>/is', function ($m) use (&$headings, &$headingIndex) {
            $id = 'section-'.$headingIndex++;
            $headings->push(['id' => $id, 'text' => trim(strip_tags($m[3]))]);

            return '<h2'.$m[2].' id="'.$id.'">'.$m[3].'</h2>';
        }, $rawContent);
    } else {
        $paragraphs = collect(explode("\n", $rawContent))->map(fn ($p) => trim($p))->filter()->values();
    }
@endphp

<x-site-layout :seoable="$post" :title="$post->title" :description="$post->excerpt">
    <div class="reading-progress" data-reading-progress></div>

    <x-page-header
        :title="$post->title"
        :image="$post->featuredImageUrl() ?: 'https://picsum.photos/seed/acco-blog-' . $post->id . '/1920/900'"
        :breadcrumbs="[__('Insights') => route('blog.index'), $post->title => null]"
    >
        <x-slot:subtitle>
            {{ __('By') }} {{ $post->author?->name ?? 'ACCO Pakistan' }} &middot; {{ $post->published_at?->format('M j, Y') }}
            @if ($post->reading_time) &middot; {{ $post->reading_time }} {{ __('min read') }} @endif
        </x-slot:subtitle>
    </x-page-header>

    <section class="section">
        <div class="container project-layout">
            <div data-article-body>
                @if ($post->featuredImageUrl())
                    <figure class="reveal-mask" style="aspect-ratio:16/9;overflow:hidden;margin-bottom:2.5rem;">
                        <img src="{{ $post->featuredImageUrl() }}" alt="{{ $post->title }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
                    </figure>
                @endif

                <div class="prose">
                    @if ($isHtml)
                        {!! $renderedContent !!}
                    @else
                        @foreach ($paragraphs as $i => $paragraph)
                            <p id="section-{{ $i }}">{{ $paragraph }}</p>
                        @endforeach
                    @endif
                </div>

                @if ($post->tags->isNotEmpty())
                    <div class="filters" style="margin-top:2.5rem;">
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="filter-pill">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                @endif

                <div class="share-row">
                    <span>{{ __('Share:') }}</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener"><x-icon name="facebook" /></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener"><x-icon name="twitter" /></a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener"><x-icon name="linkedin" /></a>
                </div>
            </div>

            <aside class="toc">
                <div class="toc__heading">{{ __('In This Article') }}</div>
                <nav class="toc__list">
                    @if ($isHtml)
                        @foreach ($headings->take(6) as $heading)
                            <a href="#{{ $heading['id'] }}" data-toc-link>{{ \Illuminate\Support\Str::limit($heading['text'], 40) }}</a>
                        @endforeach
                    @else
                        @foreach ($paragraphs->take(6) as $i => $paragraph)
                            <a href="#section-{{ $i }}" data-toc-link>{{ \Illuminate\Support\Str::words($paragraph, 6, '…') }}</a>
                        @endforeach
                    @endif
                </nav>

                @if ($relatedPosts->isNotEmpty())
                    <div class="toc__heading" style="margin-top:2.5rem;">{{ __('Related Articles') }}</div>
                    <div style="display:flex;flex-direction:column;gap:1.25rem;">
                        @foreach ($relatedPosts as $related)
                            <a href="{{ route('blog.show', $related) }}" style="display:block;">
                                <div style="font-family:var(--font-display);font-size:1rem;">{{ $related->title }}</div>
                                <div class="text-muted" style="font-size:0.8rem;margin-top:0.25rem;">{{ $related->published_at?->format('M j, Y') }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </aside>
        </div>
    </section>
</x-site-layout>
