@php
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
    $logo = \App\Models\Setting::get('logo');
    $logoDark = \App\Models\Setting::get('logo_dark');
    $phone = \App\Models\Setting::get('phone');
    $isHome = request()->routeIs('home');
    $navServices = \App\Models\Service::published()->orderBy('order')->get();
    $navCategories = \App\Models\ProjectCategory::orderBy('name')->get();
@endphp

<header class="header" data-header data-transparent="{{ $isHome ? '1' : '0' }}">
    <div class="container header__inner">
        <a href="{{ route('home') }}" class="header__brand">
            @if ($logo && $logoDark)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" alt="{{ $siteName }}" class="header__logo header__logo--light">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($logoDark) }}" alt="{{ $siteName }}" class="header__logo header__logo--dark">
            @elseif ($logo)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" alt="{{ $siteName }}" class="header__logo">
            @else
                {{ $siteName }}
            @endif
        </a>

        <nav class="header__nav" data-header-nav>
            <span class="header__link" data-mega-trigger="services">
                {{ __('Services') }} <x-icon name="chevron-down" />
            </span>
            <span class="header__link" data-mega-trigger="projects">
                {{ __('Projects') }} <x-icon name="chevron-down" />
            </span>
            <a href="{{ route('about') }}" class="header__link">{{ __('About') }}</a>
            <a href="{{ route('blog.index') }}" class="header__link">{{ __('Insights') }}</a>
            <a href="{{ route('careers.index') }}" class="header__link">{{ __('Careers') }}</a>
            <a href="{{ route('contact') }}" class="header__link">{{ __('Contact') }}</a>
        </nav>

        <div class="header__actions">
            @if ($phone)
                <a href="tel:{{ $phone }}" class="header__phone">
                    <x-icon name="phone" class="icon-sm" style="width:0.9rem;height:0.9rem;" />
                    {{ $phone }}
                </a>
            @endif
            <a href="{{ route('contact') }}" class="btn btn--outline-light btn--sm d-none-mobile">
                {{ __('Start a Project') }}
            </a>
            <button type="button" class="burger" data-menu-toggle aria-expanded="false" aria-controls="mobileMenu" aria-label="{{ __('Open menu') }}">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

{{-- Services mega menu --}}
<div class="mega" data-mega="services">
    <div class="container mega__grid">
        <div class="mega__intro">
            <div class="eyebrow">{{ __('What We Do') }}</div>
            <h3 class="display-3">{{ __('Full design-build capability, under one roof.') }}</h3>
            <a href="{{ route('services.index') }}" class="btn--ghost" style="display:inline-flex;align-items:center;gap:0.4rem;margin-top:1rem;">
                {{ __('View All Services') }} <x-icon name="arrow-right" />
            </a>
        </div>
        <div class="mega__list">
            @foreach ($navServices as $service)
                <a href="{{ route('services.show', $service) }}" class="mega__item">
                    {{ $service->title }}
                    <span>{{ sprintf('%02d', $loop->iteration) }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Projects mega menu --}}
<div class="mega" data-mega="projects">
    <div class="container mega__grid">
        <div class="mega__intro">
            <div class="eyebrow">{{ __('Our Work') }}</div>
            <h3 class="display-3">{{ __('Landmark projects across Pakistan.') }}</h3>
            <a href="{{ route('projects.index') }}" class="btn--ghost" style="display:inline-flex;align-items:center;gap:0.4rem;margin-top:1rem;">
                {{ __('View All Projects') }} <x-icon name="arrow-right" />
            </a>
        </div>
        <div class="mega__list">
            @foreach ($navCategories as $category)
                <a href="{{ route('projects.index', ['category' => $category->slug]) }}" class="mega__item">
                    {{ $category->name }}
                    <span>{{ sprintf('%02d', $loop->iteration) }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Mobile menu --}}
<div class="mobile-menu" id="mobileMenu" data-mobile-menu>
    <div class="mobile-menu__top">
        <span class="header__brand" style="color:#fff;">
            @if ($logo)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" alt="{{ $siteName }}" class="header__logo">
            @else
                {{ $siteName }}
            @endif
        </span>
        <button type="button" class="mobile-menu__close" data-menu-close aria-label="{{ __('Close menu') }}">
            <x-icon name="x" />
        </button>
    </div>

    <nav class="mobile-menu__nav">
        <div class="mobile-menu__link" data-submenu-toggle="mobileServices" role="button">
            {{ __('Services') }} <x-icon name="chevron-down" class="mobile-menu__chevron" />
        </div>
        <div class="mobile-menu__sub" id="mobileServices">
            @foreach ($navServices as $service)
                <a href="{{ route('services.show', $service) }}">{{ $service->title }}</a>
            @endforeach
        </div>

        <div class="mobile-menu__link" data-submenu-toggle="mobileProjects" role="button">
            {{ __('Projects') }} <x-icon name="chevron-down" class="mobile-menu__chevron" />
        </div>
        <div class="mobile-menu__sub" id="mobileProjects">
            <a href="{{ route('projects.index') }}">{{ __('All Projects') }}</a>
            @foreach ($navCategories as $category)
                <a href="{{ route('projects.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
            @endforeach
        </div>

        <a href="{{ route('about') }}" class="mobile-menu__link">{{ __('About') }}</a>
        <a href="{{ route('blog.index') }}" class="mobile-menu__link">{{ __('Insights') }}</a>
        <a href="{{ route('careers.index') }}" class="mobile-menu__link">{{ __('Careers') }}</a>
        <a href="{{ route('contact') }}" class="mobile-menu__link">{{ __('Contact') }}</a>
    </nav>

    <div class="mobile-menu__footer">
        @if ($phone)
            <a href="tel:{{ $phone }}">{{ $phone }}</a>
        @endif
        <div class="mobile-menu__socials">
            @if ($fb = \App\Models\Setting::get('facebook_url'))
                <a href="{{ $fb }}" target="_blank" rel="noopener"><x-icon name="facebook" /></a>
            @endif
            @if ($ig = \App\Models\Setting::get('instagram_url'))
                <a href="{{ $ig }}" target="_blank" rel="noopener"><x-icon name="instagram" /></a>
            @endif
            @if ($li = \App\Models\Setting::get('linkedin_url'))
                <a href="{{ $li }}" target="_blank" rel="noopener"><x-icon name="linkedin" /></a>
            @endif
        </div>
    </div>
</div>
