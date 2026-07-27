<x-site-layout :title="__('Our Services')" :description="__('Explore the full range of architecture, engineering, and construction services offered by ACCO Pakistan.')">
    <x-page-header
        :eyebrow="__('What We Do')"
        :title="__('Our Services')"
        subtitle="Full design-build capability, delivered by one accountable team — from first sketch to final handover."
        image="https://picsum.photos/seed/acco-services-header/1920/900"
        :breadcrumbs="[__('Services') => null]"
    />

    <section class="section">
        <div class="container">
            @if ($services->isEmpty())
                <p class="text-muted">{{ __('Services will be listed here soon.') }}</p>
            @else
                @foreach ($services as $service)
                    <div class="row-editorial {{ $loop->even ? 'is-reversed' : '' }} reveal-up">
                        <div class="row-editorial__text">
                            <div class="row-editorial__index">{{ sprintf('%02d', $loop->iteration) }} / {{ sprintf('%02d', $services->total()) }}</div>
                            <h2 class="display-3">{{ $service->title }}</h2>
                            @if ($service->hero_tagline)
                                <p class="heading-sm text-muted" style="margin-top:0.75rem;font-weight:400;">{{ $service->hero_tagline }}</p>
                            @endif
                            <p class="lede" style="margin-top:1.25rem;">{{ $service->excerpt }}</p>
                            <a href="{{ route('services.show', $service) }}" class="btn btn--primary" style="margin-top:1.75rem;">
                                {{ __('Explore Service') }} <x-icon name="arrow-right" />
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

                @if ($services->hasPages())
                    <div style="margin-top:2rem;">
                        {{ $services->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>
</x-site-layout>
