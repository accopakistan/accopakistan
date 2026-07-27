<x-site-layout :title="__('Our Projects')" :description="__('Browse ACCO Pakistan\'s portfolio of completed and ongoing architecture, engineering, and construction projects.')">
    <x-page-header
        :eyebrow="__('Our Work')"
        :title="__('Projects Portfolio')"
        subtitle="Commercial towers, hospitals, industrial plants, and residences — delivered across Pakistan."
        image="https://picsum.photos/seed/acco-projects-header/1920/900"
        :breadcrumbs="[__('Projects') => null]"
    />

    <section class="section">
        <div class="container">
            @if ($categories->isNotEmpty())
                <div class="filters reveal-up" style="margin-bottom:3rem;">
                    <a href="{{ route('projects.index') }}" class="filter-pill {{ request('category') ? '' : 'is-active' }}">{{ __('All') }}</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('projects.index', ['category' => $category->slug]) }}" class="filter-pill {{ request('category') === $category->slug ? 'is-active' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($projects->isEmpty())
                <p class="text-muted">{{ __('Projects will be listed here soon.') }}</p>
            @else
                <div class="project-grid">
                    @foreach ($projects as $index => $project)
                        <a href="{{ route('projects.show', $project) }}" class="project-tile reveal-up {{ $index % 5 === 0 ? 'is-wide' : '' }}">
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

                <div style="margin-top:3rem;">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </section>
</x-site-layout>
