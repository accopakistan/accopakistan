<x-site-layout :title="__('Careers')" :description="__('Explore open positions and join the ACCO Pakistan team.')">
    <x-page-header
        :eyebrow="__('Join Our Team')"
        :title="__('Open Positions')"
        subtitle="We are always looking for talented architects, engineers, and construction professionals."
        :image="\App\Models\Setting::imageUrl('careers_header_image', 'https://picsum.photos/seed/acco-careers-header/1920/900')"
        :breadcrumbs="[__('Careers') => null]"
    />

    <section class="section">
        <div class="container container--narrow">
            @if ($jobPostings->isEmpty())
                <p class="text-muted">{{ __('There are no open positions right now. Please check back soon.') }}</p>
            @else
                @foreach ($jobPostings as $jobPosting)
                    <a href="{{ route('careers.show', $jobPosting) }}" class="job-row reveal-up">
                        <div>
                            <div class="job-row__title">{{ $jobPosting->title }}</div>
                            <div class="job-row__meta">
                                @if ($jobPosting->department)<span>{{ $jobPosting->department }}</span>@endif
                                @if ($jobPosting->location)<span>{{ $jobPosting->location }}</span>@endif
                            </div>
                        </div>
                        <span class="job-row__tag">{{ ucfirst(str_replace('-', ' ', $jobPosting->type)) }}</span>
                    </a>
                @endforeach

                <div style="margin-top:2rem;">
                    {{ $jobPostings->links() }}
                </div>
            @endif
        </div>
    </section>
</x-site-layout>
