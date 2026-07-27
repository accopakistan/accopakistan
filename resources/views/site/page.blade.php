<x-site-layout :seoable="$page" :title="$page->title" :description="$page->excerpt">
    <x-page-header :title="$page->title" :subtitle="$page->excerpt" :image="$page->featuredImageUrl() ?: 'https://picsum.photos/seed/acco-page-' . $page->id . '/1920/900'" :breadcrumbs="[$page->title => null]" />

    <section class="section">
        <div class="container content-page">
            @foreach ($page->blocks->where('is_active', true) as $block)
                @if ($block->type === 'heading_text')
                    <div class="reveal-up" style="margin-bottom:2.5rem;">
                        @if (! empty($block->data['heading']))
                            <h2 class="display-3" style="margin-bottom:1rem;">{{ $block->data['heading'] }}</h2>
                        @endif
                        <div class="prose">
                            @foreach (explode("\n", $block->data['body'] ?? '') as $paragraph)
                                @if (trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif
                            @endforeach
                        </div>
                    </div>
                @elseif ($block->type === 'image')
                    <figure class="reveal-up" style="margin-bottom:2.5rem;">
                        <img src="{{ $block->data['url'] ?? '' }}" alt="{{ $block->data['alt'] ?? '' }}" loading="lazy">
                        @if (! empty($block->data['caption']))
                            <figcaption class="text-muted" style="font-size:0.85rem;text-align:center;margin-top:0.75rem;">{{ $block->data['caption'] }}</figcaption>
                        @endif
                    </figure>
                @elseif ($block->type === 'cta')
                    <div class="reveal-up" style="background:var(--c-ink);color:var(--c-white);padding:3rem;text-align:center;margin-bottom:2.5rem;">
                        @if (! empty($block->data['heading']))
                            <h2 class="heading-sm" style="color:var(--c-white);margin-bottom:1.25rem;">{{ $block->data['heading'] }}</h2>
                        @endif
                        @if (! empty($block->data['button_text']))
                            <a href="{{ $block->data['button_url'] ?? '#' }}" class="btn btn--gold">{{ $block->data['button_text'] }}</a>
                        @endif
                    </div>
                @elseif ($block->type === 'gallery')
                    <div class="project-gallery reveal-up">
                        @foreach ($block->data['images'] ?? [] as $image)
                            <figure><img src="{{ $image }}" loading="lazy"></figure>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    </section>
</x-site-layout>
