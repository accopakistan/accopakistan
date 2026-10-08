<x-site-layout :title="__('Our Projects')" :description="__('Browse ACCO Pakistan\'s portfolio of completed and ongoing architecture, engineering, and construction projects.')">
    <x-page-header
        :eyebrow="__('Our Work')"
        :title="__('Projects Portfolio')"
        subtitle="Commercial towers, hospitals, industrial plants, and residences — delivered across Pakistan."
        :image="\App\Models\Setting::imageUrl('projects_header_image', 'https://picsum.photos/seed/acco-projects-header/1920/900')"
        :breadcrumbs="[__('Projects') => null]"
    />

    <section class="section section--portfolio">
        <div class="container">
            @if ($categories->isNotEmpty())
                <div class="portfolio-filters reveal-up" style="margin-bottom:3.5rem;">
                    <a href="{{ route('projects.index') }}" class="filter-pill {{ request('category') ? '' : 'is-active' }}">
                        <span>{{ __('All Projects') }}</span>
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('projects.index', ['category' => $category->slug]) }}" class="filter-pill {{ request('category') === $category->slug ? 'is-active' : '' }}">
                            <span>{{ $category->name }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($projects->isEmpty())
                <div class="portfolio-empty text-center" style="padding:5rem 0;">
                    <p class="text-muted" style="font-size:1.1rem;">{{ __('No projects found in this category.') }}</p>
                    <a href="{{ route('projects.index') }}" class="btn btn--outline" style="margin-top:1.5rem;">
                        {{ __('View All Projects') }}
                    </a>
                </div>
            @else
                <div class="portfolio-grid">
                    @foreach ($projects as $project)
                        <article class="portfolio-card reveal-up">
                            <a href="{{ route('projects.show', $project) }}" class="portfolio-card__link">
                                <div class="portfolio-card__media">
                                    @if ($project->featuredImageUrl())
                                        <img src="{{ $project->featuredImageUrl() }}" alt="{{ $project->title }}" loading="lazy">
                                    @else
                                        <img src="https://picsum.photos/seed/acco-proj-{{ $project->id }}/900/1100" alt="{{ $project->title }}" loading="lazy">
                                    @endif
                                    <div class="portfolio-card__badges">
                                        @if ($project->category)
                                            <span class="portfolio-card__badge">{{ $project->category->name }}</span>
                                        @endif
                                        @if ($project->completion_date)
                                            <span class="portfolio-card__year">{{ $project->completion_date->format('Y') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="portfolio-card__body">
                                    <div class="portfolio-card__meta">
                                        @if ($project->location)
                                            <span class="portfolio-card__meta-item">
                                                <x-icon name="map-pin" style="width:0.8rem;height:0.8rem;" />
                                                {{ $project->location }}
                                            </span>
                                        @endif
                                        @if ($project->area)
                                            <span class="portfolio-card__meta-item">
                                                <x-icon name="building" style="width:0.8rem;height:0.8rem;" />
                                                {{ $project->area }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="portfolio-card__title">{{ $project->title }}</h3>

                                    @if ($project->excerpt)
                                        <p class="portfolio-card__excerpt">{{ Str::limit($project->excerpt, 110) }}</p>
                                    @endif

                                    <div class="portfolio-card__action">
                                        <span class="portfolio-card__cta">{{ __('Explore Project') }}</span>
                                        <span class="portfolio-card__arrow">
                                            <x-icon name="arrow-up-right" style="width:0.95rem;height:0.95rem;" />
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <div class="portfolio-pagination-wrap" style="margin-top:3.5rem;">
                    {{ $projects->links('vendor.pagination.site') }}
                </div>
            @endif
        </div>
    </section>
</x-site-layout>
