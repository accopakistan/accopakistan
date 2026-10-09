@php
    use App\Models\Setting;
    use Illuminate\Support\Facades\Storage;

    $heroImage = Setting::get('hero_image') ? Storage::disk('public')->url(Setting::get('hero_image')) : 'https://picsum.photos/seed/acco-hero-main/1920/1280';
    $aboutImage = Setting::get('about_image') ? Storage::disk('public')->url(Setting::get('about_image')) : 'https://picsum.photos/seed/acco-about-main/1200/1500';
@endphp

<x-site-layout :title="Setting::get('hero_heading')" :description="Setting::get('intro_content')">

    {{-- ============================== HERO ============================== --}}
    <section class="hero">
        <div class="hero__media"><img src="{{ $heroImage }}" alt=""></div>
        <div class="container hero__content">
            <div class="eyebrow hero__eyebrow" style="color:var(--c-gold-light);">{{ Setting::get('tagline') }}</div>
            <h1 class="hero__title display-1" data-reveal-text>
                <span class="line"><span>{{ Setting::get('hero_heading') }}</span></span>
            </h1>
            <p class="hero__sub lede" style="color:rgba(255,255,255,0.82);">{{ Setting::get('hero_subheading') }}</p>
            <div class="hero__actions">
                <a href="{{ route('contact') }}" class="btn btn--gold">{{ __('Get a Quote') }} <x-icon name="arrow-up-right" /></a>
                <a href="{{ route('projects.index') }}" class="btn btn--outline-light">{{ __('View Our Work') }} <x-icon name="arrow-right" /></a>
            </div>
        </div>
        <div class="hero__scroll"><span>{{ __('Scroll') }}</span><span class="hero__scroll-line"></span></div>
    </section>

    {{-- ============================== ABOUT ============================== --}}
    <section class="section">
        <div class="container row-editorial">
            <div class="row-editorial__text reveal-up">
                <div class="eyebrow">{{ __('Who We Are') }}</div>
                <h2 class="display-2" style="margin-top:1rem;">{{ Setting::get('intro_heading') }}</h2>
                <p class="lede" style="margin-top:1.5rem;">{{ Setting::get('intro_content') }}</p>
                <a href="{{ route('about') }}" class="btn btn--primary" style="margin-top:2rem;">{{ __('More About Us') }} <x-icon name="arrow-right" /></a>
            </div>
            <div class="row-editorial__media reveal-mask">
                <img src="{{ $aboutImage }}" alt="{{ Setting::get('site_name') }}" loading="lazy">
            </div>
        </div>
    </section>

    {{-- ============================== STATS ============================== --}}
    <section class="section section--tight" style="background:var(--c-navy);color:var(--c-white);">
        <div class="container">
            <div class="stats stats--dark">
                <div class="stat"><div class="stat__value num" data-count-to="{{ $stats['years_experience'] }}" data-count-suffix="+">{{ $stats['years_experience'] }}+</div><div class="stat__label">{{ __('Years Experience') }}</div></div>
                <div class="stat"><div class="stat__value num" data-count-to="{{ $stats['projects_completed'] }}" data-count-suffix="+">{{ $stats['projects_completed'] }}+</div><div class="stat__label">{{ __('Projects Completed') }}</div></div>
                <div class="stat"><div class="stat__value num" data-count-to="{{ $stats['happy_clients'] }}" data-count-suffix="+">{{ $stats['happy_clients'] }}+</div><div class="stat__label">{{ __('Happy Clients') }}</div></div>
                <div class="stat"><div class="stat__value num" data-count-to="{{ $stats['awards_won'] }}" data-count-suffix="+">{{ $stats['awards_won'] }}+</div><div class="stat__label">{{ __('Awards Won') }}</div></div>
            </div>
        </div>
    </section>

    {{-- ============================== INDUSTRIES ============================== --}}
    @if ($industries->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('Who We Serve') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;">{{ __('Industries') }}</h2>
                    </div>
                </div>
                <div class="industry-grid reveal-up">
                    @foreach ($industries as $industry)
                        <div class="industry">
                            <h3 class="heading-sm">{{ $industry['title'] }}</h3>
                            <p>{{ $industry['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================== SERVICES ============================== --}}
    @if ($featuredServices->isNotEmpty())
        <section class="section" style="background:var(--bg-alt);">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('What We Do') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;">{{ __('Our Services') }}</h2>
                    </div>
                    <a href="{{ route('services.index') }}" class="btn--ghost reveal-up" style="display:inline-flex;align-items:center;gap:0.4rem;">
                        {{ __('View All Services') }} <x-icon name="arrow-right" />
                    </a>
                </div>

                @foreach ($featuredServices as $service)
                    <div class="row-editorial {{ $loop->even ? 'is-reversed' : '' }} reveal-up">
                        <div class="row-editorial__text">
                            <div class="row-editorial__index">{{ sprintf('%02d', $loop->iteration) }} / {{ sprintf('%02d', $featuredServices->count()) }}</div>
                            <h3 class="display-3">{{ $service->title }}</h3>
                            <p class="lede" style="margin-top:1rem;">{{ $service->excerpt }}</p>
                            <a href="{{ route('services.show', $service) }}" class="btn btn--outline" style="margin-top:1.75rem;">
                                {{ __('Learn More') }} <x-icon name="arrow-right" />
                            </a>
                        </div>
                        <div class="row-editorial__media reveal-mask">
                            @if ($service->featuredImageUrl())
                                <img src="{{ $service->featuredImageUrl() }}" alt="{{ $service->title }}" loading="lazy">
                            @else
                                <img src="https://picsum.photos/seed/acco-svc-{{ $service->id }}/1200/900" alt="{{ $service->title }}" loading="lazy">
                            @endif
                        </div>
                    </div>
                @endforeach

                @if ($otherServices->isNotEmpty())
                    <div class="more-list">
                        @foreach ($otherServices as $service)
                            <a href="{{ route('services.show', $service) }}" class="more-list__item">
                                {{ $service->title }}
                                <span>{{ sprintf('%02d', $loop->iteration) }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ============================== FEATURED PROJECTS CAROUSEL ============================== --}}
    @if ($featuredProjects->isNotEmpty())
        <section class="section section--projects-carousel">
            <div class="container">
                <div class="section-head section-head--carousel">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('Our Work') }}</div>
                        <h2 class="display-2" style="margin-top:0.75rem;">{{ __('Featured Projects') }}</h2>
                        <p class="lede" style="margin-top:0.75rem;max-width:38rem;">{{ __('Signature commercial, healthcare, industrial, and residential landmarks delivered across Pakistan.') }}</p>
                    </div>
                    <div class="carousel-head-actions reveal-up">
                        <a href="{{ route('projects.index') }}" class="btn--ghost d-none-mobile" style="display:inline-flex;align-items:center;gap:0.4rem;">
                            {{ __('View All Projects') }} <x-icon name="arrow-right" />
                        </a>
                        <div class="carousel-controls">
                            <div class="carousel-counter">
                                <span class="carousel-counter__current" data-proj-counter-current>01</span>
                                <span class="carousel-counter__sep">/</span>
                                <span class="carousel-counter__total">{{ sprintf('%02d', $featuredProjects->count()) }}</span>
                            </div>
                            <div class="carousel-nav-group">
                                <button type="button" class="carousel-nav-btn" data-proj-prev aria-label="{{ __('Previous Project') }}">
                                    <x-icon name="arrow-left" />
                                </button>
                                <button type="button" class="carousel-nav-btn" data-proj-next aria-label="{{ __('Next Project') }}">
                                    <x-icon name="arrow-right" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="project-carousel-container container reveal-up" data-project-carousel>
                <div class="project-carousel-track" data-project-track tabindex="0" role="region" aria-label="{{ __('Featured Projects Carousel') }}">
                    @foreach ($featuredProjects as $index => $project)
                        <div class="project-carousel-slide" data-project-slide data-index="{{ $index }}">
                            <article class="proj-card">
                                <div class="proj-card__media-wrap">
                                    <a href="{{ route('projects.show', $project) }}" class="proj-card__media-link" tabindex="-1">
                                        @if ($project->featuredImageUrl())
                                            <img src="{{ $project->featuredImageUrl() }}" alt="{{ $project->title }}" loading="lazy" class="proj-card__img">
                                        @else
                                            <img src="https://picsum.photos/seed/acco-proj-{{ $project->id }}/1200/900" alt="{{ $project->title }}" loading="lazy" class="proj-card__img">
                                        @endif
                                    </a>
                                    <div class="proj-card__badges">
                                        @if ($project->category)
                                            <span class="proj-card__badge">{{ $project->category->name }}</span>
                                        @endif
                                        @if ($project->completion_date)
                                            <span class="proj-card__year">{{ $project->completion_date->format('Y') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="proj-card__content">
                                    <div class="proj-card__meta">
                                        @if ($project->location)
                                            <span class="proj-card__meta-item">
                                                <x-icon name="map-pin" style="width:0.85rem;height:0.85rem;" />
                                                {{ $project->location }}
                                            </span>
                                        @endif
                                        @if ($project->area)
                                            <span class="proj-card__meta-item">
                                                <x-icon name="building" style="width:0.85rem;height:0.85rem;" />
                                                {{ $project->area }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="proj-card__title">
                                        <a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a>
                                    </h3>

                                    @if ($project->excerpt)
                                        <p class="proj-card__excerpt">{{ Str::limit($project->excerpt, 110) }}</p>
                                    @endif

                                    <div class="proj-card__footer">
                                        <a href="{{ route('projects.show', $project) }}" class="proj-card__cta">
                                            <span>{{ __('Explore Project') }}</span>
                                            <span class="proj-card__cta-icon">
                                                <x-icon name="arrow-up-right" style="width:0.95rem;height:0.95rem;" />
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="project-carousel-bottom">
                    <div class="project-carousel-progress">
                        <div class="project-carousel-progress__bar" data-proj-progress></div>
                    </div>
                    <div class="project-carousel-dots" data-proj-dots>
                        @foreach ($featuredProjects as $index => $project)
                            <button type="button" class="project-carousel-dot {{ $index === 0 ? 'is-active' : '' }}" data-proj-dot="{{ $index }}" aria-label="{{ __('Go to project slide :num', ['num' => $index + 1]) }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================== CASE STUDY SPOTLIGHT ============================== --}}
    @if ($flagshipProject)
        <section class="section" style="background:var(--bg-alt);">
            <div class="container">
                <div class="eyebrow reveal-up">{{ __('Case Study') }}</div>
                <div class="post-feature reveal-up" style="margin-top:2rem;">
                    <div class="post-feature__media">
                        @if ($flagshipProject->featuredImageUrl())
                            <img src="{{ $flagshipProject->featuredImageUrl() }}" alt="{{ $flagshipProject->title }}" loading="lazy">
                        @else
                            <img src="https://picsum.photos/seed/acco-flagship/1200/900" alt="{{ $flagshipProject->title }}" loading="lazy">
                        @endif
                    </div>
                    <div>
                        <h3 class="display-2">{{ $flagshipProject->title }}</h3>
                        <p class="lede" style="margin-top:1.25rem;">{{ $flagshipProject->excerpt }}</p>
                        <div class="grid" style="margin-top:2rem;max-width:26rem;">
                            @if ($flagshipProject->location)
                                <div class="col-6"><div class="caps text-muted">{{ __('Location') }}</div><div style="margin-top:0.35rem;">{{ $flagshipProject->location }}</div></div>
                            @endif
                            @if ($flagshipProject->area)
                                <div class="col-6"><div class="caps text-muted">{{ __('Area') }}</div><div style="margin-top:0.35rem;">{{ $flagshipProject->area }}</div></div>
                            @endif
                            @if ($flagshipProject->project_value)
                                <div class="col-6"><div class="caps text-muted">{{ __('Value') }}</div><div style="margin-top:0.35rem;">{{ $flagshipProject->project_value }}</div></div>
                            @endif
                            @if ($flagshipProject->completion_date)
                                <div class="col-6"><div class="caps text-muted">{{ __('Completed') }}</div><div style="margin-top:0.35rem;">{{ $flagshipProject->completion_date->format('M Y') }}</div></div>
                            @endif
                        </div>
                        <a href="{{ route('projects.show', $flagshipProject) }}" class="btn btn--primary" style="margin-top:2rem;">
                            {{ __('Read the Full Story') }} <x-icon name="arrow-right" />
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================== VALUES ============================== --}}
    @if ($values->isNotEmpty())
        <section class="section" style="background:var(--c-ink);color:var(--c-white);">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('Why ACCO Pakistan') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;color:var(--c-white);">{{ __('What We Stand For') }}</h2>
                    </div>
                </div>
                <div class="feature-grid">
                    @foreach ($values as $i => $value)
                        <div class="feature reveal-up">
                            <span class="feature__num">{{ sprintf('%02d', $i + 1) }}</span>
                            <h3 class="heading-sm" style="color:var(--c-white);">{{ $value['title'] }}</h3>
                            <p>{{ $value['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================== PROCESS ============================== --}}
    @if ($processSteps->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('How We Work') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;">{{ __('Our Process') }}</h2>
                    </div>
                </div>
                <div class="process reveal-up">
                    @foreach ($processSteps as $i => $step)
                        <div class="process__step">
                            <span class="process__num">{{ sprintf('%02d', $i + 1) }}</span>
                            <h3 class="heading-sm">{{ $step['title'] }}</h3>
                            <p>{{ $step['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================== AWARDS ============================== --}}
    @if ($awards->isNotEmpty())
        <section class="section section--tight" style="background:var(--bg-alt);">
            <div class="container row-editorial">
                <div class="reveal-up">
                    <div class="eyebrow">{{ __('Recognition') }}</div>
                    <h2 class="display-2" style="margin-top:1rem;">{{ __('Awards') }}</h2>
                    <p class="lede" style="margin-top:1rem;">{{ __('Recognized by Pakistan\'s leading industry bodies for design, safety, and sustainability.') }}</p>
                </div>
                <div class="reveal-up">
                    @foreach ($awards as $award)
                        <div class="award-row">
                            <div>
                                <div class="award-row__title">{{ $award['title'] }}</div>
                                <div class="award-row__org">{{ $award['org'] }}</div>
                            </div>
                            <div class="award-row__year">{{ $award['year'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================== GLOBAL PRESENCE ============================== --}}
    @if ($offices->isNotEmpty())
        <section class="section" style="background:var(--c-navy);color:var(--c-white);">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('Where We Work') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;color:var(--c-white);">{{ __('National Presence') }}</h2>
                    </div>
                </div>
                <div class="office-grid reveal-up">
                    @foreach ($offices as $office)
                        <div class="office">
                            <h3 class="heading-sm" style="color:var(--c-white);">{{ $office['city'] }}</h3>
                            <p>{{ $office['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================== TESTIMONIALS ============================== --}}
    @if ($testimonials->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="eyebrow reveal-up">{{ __('Client Feedback') }}</div>
                <div class="testi-slider reveal-up" data-testi-slider style="margin-top:2rem;">
                    <div class="testi-track" data-testi-track>
                        @foreach ($testimonials as $testimonial)
                            <div class="testi-slide" data-testi-slide>
                                <div class="testi">
                                    @if ($testimonial->rating)
                                        <div class="testi__stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <x-icon name="star" style="{{ $i <= $testimonial->rating ? '' : 'opacity:0.25;' }}" />
                                            @endfor
                                        </div>
                                    @endif
                                    <p class="testi__quote">&ldquo;{{ $testimonial->quote }}&rdquo;</p>
                                    <div class="testi__author">
                                        <div class="testi__avatar">
                                            @if ($testimonial->photoUrl())
                                                <img src="{{ $testimonial->photoUrl() }}" alt="">
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight:600;">{{ $testimonial->client_name }}</div>
                                            <div class="text-muted" style="font-size:0.85rem;">{{ $testimonial->client_position }}{{ $testimonial->company ? ', '.$testimonial->company : '' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="testi-nav">
                        @foreach ($testimonials as $i => $testimonial)
                            <button type="button" class="testi-dot {{ $i === 0 ? 'is-active' : '' }}" data-testi-dot aria-label="{{ __('Testimonial') }} {{ $i + 1 }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================== CLIENTS ============================== --}}
    @if ($clients->isNotEmpty())
        <section class="section section--tight" style="background:var(--bg-alt);">
            <div class="container">
                <div class="eyebrow reveal-up" style="justify-content:center;">{{ __('Trusted By') }}</div>
                <div class="client-row reveal-up" style="margin-top:2rem;">
                    @foreach ($clients as $client)
                        <span class="client-mark">{{ $client->name }}</span>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================== INSIGHTS ============================== --}}
    @if ($latestPosts->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('Insights') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;">{{ __('Latest From ACCO') }}</h2>
                    </div>
                    <a href="{{ route('blog.index') }}" class="btn--ghost reveal-up" style="display:inline-flex;align-items:center;gap:0.4rem;">
                        {{ __('View All Articles') }} <x-icon name="arrow-right" />
                    </a>
                </div>
                <div class="grid">
                    @foreach ($latestPosts as $post)
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
            </div>
        </section>
    @endif

    {{-- ============================== CAREERS CTA ============================== --}}
    <section class="section" style="background:var(--c-ink);color:var(--c-white);text-align:center;">
        <div class="container reveal-up">
            <div class="eyebrow" style="justify-content:center;">{{ __('Careers') }}</div>
            <h2 class="display-2" style="margin-top:1rem;color:var(--c-white);">{{ __('Join Our Team') }}</h2>
            <p class="lede" style="margin:1.25rem auto 0;max-width:32rem;">{{ __('We are always looking for talented architects, engineers, and construction professionals.') }}</p>
            <a href="{{ route('careers.index') }}" class="btn btn--gold" style="margin-top:2rem;">{{ __('View Open Positions') }} <x-icon name="arrow-right" /></a>
        </div>
    </section>

    {{-- ============================== CONTACT ============================== --}}
    <section class="section">
        <div class="container">
            <div class="row-editorial">
                <div class="reveal-up">
                    <div class="eyebrow">{{ __('Get In Touch') }}</div>
                    <h2 class="display-2" style="margin-top:1rem;">{{ __('Start Your Project') }}</h2>
                    <div style="margin-top:2rem;">
                        @include('site.partials.contact-form')
                    </div>
                </div>
                <div class="reveal-up">
                    @if ($mapEmbed = Setting::get('google_map_embed'))
                        <div class="map-frame">{!! $mapEmbed !!}</div>
                    @else
                        <div class="map-frame"><img src="https://picsum.photos/seed/acco-map/900/900" alt=""></div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-site-layout>
