<x-site-layout :seoable="$project" :title="$project->title" :description="$project->excerpt">
    <x-page-header
        :title="$project->title"
        :subtitle="$project->excerpt"
        :image="$project->featuredImageUrl() ?: 'https://picsum.photos/seed/acco-project-' . $project->id . '/1920/900'"
        :breadcrumbs="[__('Projects') => route('projects.index'), $project->title => null]"
    />

    <section class="section">
        <div class="container project-layout">
            <div>
                <div class="eyebrow reveal-up">{{ __('Overview') }}</div>
                <div class="prose reveal-up" style="margin-top:1.5rem;">
                    @foreach (explode("\n", $project->content ?? '') as $paragraph)
                        @if (trim($paragraph) !== '')
                            <p>{{ $paragraph }}</p>
                        @endif
                    @endforeach
                </div>

                @if ($project->scope)
                    <h3 class="heading-sm reveal-up" style="margin-top:2.5rem;">{{ __('Scope of Work') }}</h3>
                    <p class="text-muted reveal-up" style="margin-top:0.75rem;">{{ $project->scope }}</p>
                @endif

                @if ($project->features)
                    <h3 class="heading-sm reveal-up" style="margin-top:2rem;">{{ __('Key Features') }}</h3>
                    <p class="text-muted reveal-up" style="margin-top:0.75rem;">{{ $project->features }}</p>
                @endif

                @if ($project->getMedia('gallery')->isNotEmpty())
                    <h3 class="heading-sm reveal-up" style="margin-top:2.5rem;">{{ __('Gallery') }}</h3>
                    <div class="project-gallery reveal-up">
                        @foreach ($project->getMedia('gallery') as $media)
                            <figure><img src="{{ $media->getUrl() }}" alt="{{ $project->title }}" loading="lazy"></figure>
                        @endforeach
                    </div>
                @endif

                @if (!empty($project->milestones))
                    <h3 class="heading-sm reveal-up" style="margin-top:2.5rem;">{{ __('Project Timeline') }}</h3>
                    <div class="timeline reveal-up" style="margin-top:1.5rem;">
                        @foreach ($project->milestones as $milestone)
                            <div class="timeline__item">
                                <div class="timeline__year">{{ $milestone['title'] }}</div>
                                <p>{{ $milestone['date'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="project-facts reveal-up">
                <h3 class="heading-sm" style="margin-bottom:1rem;">{{ __('Project Details') }}</h3>
                <dl style="margin:0;">
                    @if ($project->category)
                        <div class="fact-row"><dt>{{ __('Category') }}</dt><dd>{{ $project->category->name }}</dd></div>
                    @endif
                    @if ($project->client)
                        <div class="fact-row"><dt>{{ __('Client') }}</dt><dd>{{ $project->client }}</dd></div>
                    @endif
                    @if ($project->location)
                        <div class="fact-row"><dt>{{ __('Location') }}</dt><dd>{{ $project->location }}</dd></div>
                    @endif
                    @if ($project->completion_date)
                        <div class="fact-row"><dt>{{ __('Completed') }}</dt><dd>{{ $project->completion_date->format('M Y') }}</dd></div>
                    @endif
                    @if ($project->project_value)
                        <div class="fact-row"><dt>{{ __('Value') }}</dt><dd>{{ $project->project_value }}</dd></div>
                    @endif
                    @if ($project->area)
                        <div class="fact-row"><dt>{{ __('Area') }}</dt><dd>{{ $project->area }}</dd></div>
                    @endif
                </dl>

                @if (!empty($project->services_involved))
                    <h3 class="heading-sm" style="margin:2rem 0 1rem;">{{ __('Services Involved') }}</h3>
                    <ul style="display:flex;flex-direction:column;gap:0.6rem;">
                        @foreach ($project->services_involved as $service)
                            <li style="font-size:0.9rem;display:flex;align-items:baseline;gap:0.6rem;">
                                <span style="width:0.35rem;height:0.35rem;border-radius:999px;background:var(--accent);flex-shrink:0;"></span>
                                {{ $service }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                <a href="{{ route('contact') }}" class="btn btn--primary" style="width:100%;justify-content:center;margin-top:2rem;">
                    {{ __('Discuss a Similar Project') }} <x-icon name="arrow-right" />
                </a>
            </aside>
        </div>
    </section>

    @if ($relatedProjects->isNotEmpty())
        <section class="section" style="background:var(--bg-alt);">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('More Work') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;">{{ __('Related Projects') }}</h2>
                    </div>
                </div>
                <div class="project-grid">
                    @foreach ($relatedProjects as $related)
                        <a href="{{ route('projects.show', $related) }}" class="project-tile reveal-up">
                            <div class="project-tile__media">
                                @if ($related->featuredImageUrl())
                                    <img src="{{ $related->featuredImageUrl() }}" alt="{{ $related->title }}" loading="lazy">
                                @else
                                    <img src="https://picsum.photos/seed/acco-proj-{{ $related->id }}/900/1100" alt="{{ $related->title }}" loading="lazy">
                                @endif
                            </div>
                            <div class="project-tile__overlay">
                                <div class="project-tile__title">{{ $related->title }}</div>
                                <div class="project-tile__loc">{{ $related->location }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-site-layout>
