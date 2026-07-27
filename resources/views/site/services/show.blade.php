<x-site-layout :seoable="$service" :title="$service->title" :description="$service->excerpt">
    <x-page-header
        :eyebrow="__('Services')"
        :title="$service->title"
        :subtitle="$service->hero_tagline"
        :image="$service->featuredImageUrl() ?: 'https://picsum.photos/seed/acco-service-' . $service->id . '/1920/900'"
        :breadcrumbs="[__('Services') => route('services.index'), $service->title => null]"
    />

    {{-- Overview / full detail --}}
    <section class="section">
        <div class="container container--narrow">
            <div class="eyebrow reveal-up">{{ __('Overview') }}</div>
            <h2 class="display-2 reveal-up" style="margin-top:1rem;margin-bottom:2rem;">{{ __('Everything You Need To Know') }}</h2>
            <div class="prose reveal-up">
                {!! $service->content !!}
            </div>
        </div>
    </section>

    {{-- Benefits --}}
    @if (!empty($service->benefits))
        <section class="section" style="background:var(--c-ink);color:var(--c-white);">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('Why It Matters') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;color:var(--c-white);">{{ __('Benefits') }}</h2>
                    </div>
                </div>
                <div class="feature-grid">
                    @foreach ($service->benefits as $i => $benefit)
                        <div class="feature reveal-up">
                            <span class="feature__num">{{ sprintf('%02d', $i + 1) }}</span>
                            <h3 class="heading-sm" style="color:var(--c-white);">{{ $benefit['title'] }}</h3>
                            <p>{{ $benefit['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Process --}}
    @if (!empty($service->process_steps))
        <section class="section">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('How We Deliver') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;">{{ __('Our Process') }}</h2>
                    </div>
                </div>
                <div class="process reveal-up">
                    @foreach ($service->process_steps as $i => $step)
                        <div class="process__step">
                            <span class="process__num">{{ sprintf('%02d', $i + 1) }}</span>
                            <h3 class="heading-sm">{{ $step['title'] }}</h3>
                            <p>{{ $step['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Comparison table --}}
    @if (!empty($service->comparison_table['rows']))
        <section class="section" style="background:var(--bg-alt);">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('At A Glance') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;">{{ $service->comparison_table['title'] ?? __('Comparison') }}</h2>
                    </div>
                </div>
                <div class="compare reveal-up">
                    <table>
                        @if (!empty($service->comparison_table['headers']))
                            <thead>
                                <tr>
                                    @foreach ($service->comparison_table['headers'] as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                        @endif
                        <tbody>
                            @foreach ($service->comparison_table['rows'] as $row)
                                <tr>
                                    @foreach ($row as $i => $cell)
                                        @if ($i === 0)
                                            <th scope="row">{{ $cell }}</th>
                                        @else
                                            <td>{{ $cell }}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif

    {{-- Gallery --}}
    @if ($service->getMedia('gallery')->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="eyebrow reveal-up">{{ __('Gallery') }}</div>
                <div class="project-gallery reveal-up" style="margin-top:2rem;">
                    @foreach ($service->getMedia('gallery') as $media)
                        <figure><img src="{{ $media->getUrl() }}" alt="{{ $service->title }}" loading="lazy"></figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Related Projects --}}
    @if ($relatedProjects->isNotEmpty())
        <section class="section" style="background:var(--bg-alt);">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('Proof of Work') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;">{{ __('Related Projects') }}</h2>
                    </div>
                </div>
                <div class="project-grid">
                    @foreach ($relatedProjects as $project)
                        <a href="{{ route('projects.show', $project) }}" class="project-tile reveal-up">
                            <div class="project-tile__media">
                                @if ($project->featuredImageUrl())
                                    <img src="{{ $project->featuredImageUrl() }}" alt="{{ $project->title }}" loading="lazy">
                                @else
                                    <img src="https://picsum.photos/seed/acco-proj-{{ $project->id }}/900/1100" alt="{{ $project->title }}" loading="lazy">
                                @endif
                            </div>
                            <div class="project-tile__overlay">
                                @if ($project->category)<div class="project-tile__cat">{{ $project->category->name }}</div>@endif
                                <div class="project-tile__title">{{ $project->title }}</div>
                                <div class="project-tile__loc">{{ $project->location }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    @if (!empty($faqs))
        <section class="section">
            <div class="container container--narrow">
                <div class="eyebrow reveal-up">{{ __('Questions') }}</div>
                <h2 class="display-2 reveal-up" style="margin-top:1rem;margin-bottom:2.5rem;">{{ __('Frequently Asked') }}</h2>
                <div data-accordion-group class="reveal-up">
                    @foreach ($faqs as $faq)
                        <div class="accordion-item">
                            <button type="button" class="accordion-trigger" data-accordion-trigger aria-expanded="false">
                                {{ $faq['question'] }} <x-icon name="plus" />
                            </button>
                            <div class="accordion-panel">
                                <div class="accordion-panel__inner">{{ $faq['answer'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
            ])->all(),
        ]) !!}
        </script>
    @endif

    {{-- CTA --}}
    <section class="section" style="background:var(--c-navy);color:var(--c-white);text-align:center;">
        <div class="container reveal-up">
            <h2 class="display-2" style="color:var(--c-white);">{{ __('Ready to start your project?') }}</h2>
            <p class="lede" style="margin:1.25rem auto 0;max-width:30rem;">{{ __('Tell us about your project and our team will respond within one business day.') }}</p>
            <a href="{{ route('contact') }}" class="btn btn--gold" style="margin-top:2rem;">{{ __('Get a Quote') }} <x-icon name="arrow-up-right" /></a>
        </div>
    </section>

    @if ($relatedServices->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="eyebrow reveal-up">{{ __('Related Services') }}</div>
                <div class="grid" style="margin-top:2rem;">
                    @foreach ($relatedServices as $related)
                        <a href="{{ route('services.show', $related) }}" class="col-md-4 reveal-up" style="border-top:1px solid var(--border);padding-top:1.25rem;display:block;">
                            <h3 class="heading-sm">{{ $related->title }}</h3>
                            <p class="text-muted" style="font-size:0.9rem;margin-top:0.5rem;">{{ \Illuminate\Support\Str::limit($related->excerpt, 90) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-site-layout>
