@php use App\Models\Setting; @endphp

<x-site-layout :title="Setting::get('about_heading')" :description="Setting::get('about_content')">
    <x-page-header
        :eyebrow="__('About Us')"
        :title="Setting::get('about_heading')"
        :image="Setting::imageUrl('about_header_image', 'https://picsum.photos/seed/acco-about-header/1920/900')"
        :breadcrumbs="[__('About') => null]"
    />

    {{-- Intro --}}
    <section class="section">
        <div class="container row-editorial">
            <div class="row-editorial__media reveal-mask">
                <img src="{{ Setting::imageUrl('about_image', 'https://picsum.photos/seed/acco-about/1200/1500') }}" alt="{{ Setting::get('site_name') }}" loading="lazy">
            </div>
            <div class="reveal-up">
                <div class="eyebrow">{{ __('Our Story') }}</div>
                <h2 class="display-2" style="margin-top:1rem;">{{ __('A Design-Build Practice, Built On Accountability') }}</h2>
                <p class="lede" style="margin-top:1.25rem;">{{ Setting::get('about_content') }}</p>
            </div>
        </div>
    </section>

    {{-- Mission / Vision --}}
    <section class="section section--tight" style="background:var(--c-navy);color:var(--c-white);">
        <div class="container">
            <div class="grid">
                <div class="col-md-6 reveal-up">
                    <div class="eyebrow">{{ __('Our Mission') }}</div>
                    <p class="display-3" style="margin-top:1.25rem;">{{ Setting::get('mission') }}</p>
                </div>
                <div class="col-md-6 reveal-up">
                    <div class="eyebrow">{{ __('Our Vision') }}</div>
                    <p class="display-3" style="margin-top:1.25rem;">{{ Setting::get('vision') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Company Story timeline --}}
    @if ($story->isNotEmpty())
        <section class="section">
            <div class="container row-editorial">
                <div class="reveal-up">
                    <div class="eyebrow">{{ __('Our Journey') }}</div>
                    <h2 class="display-2" style="margin-top:1rem;">{{ __('Company Timeline') }}</h2>
                </div>
                <div class="timeline reveal-up">
                    @foreach ($story as $item)
                        <div class="timeline__item">
                            <div class="timeline__year">{{ $item['year'] }}</div>
                            <p>{{ $item['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Values --}}
    @if ($values->isNotEmpty())
        <section class="section" style="background:var(--c-ink);color:var(--c-white);">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('What Guides Us') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;color:var(--c-white);">{{ __('Our Values') }}</h2>
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

    {{-- Leadership --}}
    @if ($team->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="section-head">
                    <div class="section-head__text reveal-up">
                        <div class="eyebrow">{{ __('Meet The Team') }}</div>
                        <h2 class="display-2" style="margin-top:1rem;">{{ __('Our Leadership') }}</h2>
                    </div>
                </div>
                <div class="team-grid">
                    @foreach ($team as $member)
                        <div class="team-card reveal-up">
                            <div class="team-card__media">
                                @if ($member->photoUrl())
                                    <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" loading="lazy">
                                @else
                                    <img src="https://picsum.photos/seed/acco-team-{{ $member->id }}/500/625" alt="{{ $member->name }}" loading="lazy">
                                @endif
                            </div>
                            <h3>{{ $member->name }}</h3>
                            <p>{{ $member->position }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Awards --}}
    @if ($awards->isNotEmpty())
        <section class="section section--tight" style="background:var(--bg-alt);">
            <div class="container container--narrow">
                <div class="eyebrow reveal-up" style="justify-content:center;">{{ __('Recognition') }}</div>
                <h2 class="display-2 reveal-up" style="margin-top:1rem;text-align:center;">{{ __('Awards') }}</h2>
                <div class="reveal-up" style="margin-top:2.5rem;">
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
</x-site-layout>
