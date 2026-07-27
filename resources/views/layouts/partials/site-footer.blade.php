@php
    $footerMenu = \App\Models\Menu::where('location', 'footer')->where('is_active', true)->with('topLevelItems')->first();
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
    $tagline = \App\Models\Setting::get('tagline');
    $address = \App\Models\Setting::get('address');
    $phone = \App\Models\Setting::get('phone');
    $mobile = \App\Models\Setting::get('mobile');
    $email = \App\Models\Setting::get('email');
    $socials = [
        'facebook' => \App\Models\Setting::get('facebook_url'),
        'instagram' => \App\Models\Setting::get('instagram_url'),
        'linkedin' => \App\Models\Setting::get('linkedin_url'),
        'youtube' => \App\Models\Setting::get('youtube_url'),
        'twitter' => \App\Models\Setting::get('twitter_url'),
    ];
    $footerServices = \App\Models\Service::published()->orderBy('order')->limit(6)->get();
    $logo = \App\Models\Setting::get('logo');
    $footerSeoText = \App\Models\Setting::get('footer_seo_text');
@endphp

<section class="footer-cta">
    <div class="container">
        <div class="eyebrow" style="justify-content:center;">{{ __('Start a Project') }}</div>
        <h2 class="display-1 reveal-up">{{ __("Let's build something exceptional together.") }}</h2>
        <a href="{{ route('contact') }}" class="btn btn--gold">
            {{ __('Get in Touch') }} <x-icon name="arrow-up-right" />
        </a>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div>
                <div class="footer__brand">
                    @if ($logo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}" alt="{{ $siteName }}" class="footer__logo">
                    @else
                        {{ $siteName }}
                    @endif
                </div>
                @if ($footerSeoText ?: $tagline)
                    <p style="max-width:20rem;font-size:0.92rem;">{{ $footerSeoText ?: $tagline }}</p>
                @endif
                <div class="footer__socials">
                    @foreach ($socials as $network => $url)
                        @if ($url)
                            <a href="{{ $url }}" target="_blank" rel="noopener"><x-icon :name="$network" /></a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div>
                <div class="footer__heading">{{ __('Quick Links') }}</div>
                <ul class="footer__links">
                    @forelse ($footerMenu?->topLevelItems ?? [] as $item)
                        <li><a href="{{ $item->resolvedUrl() }}">{{ $item->title }}</a></li>
                    @empty
                        <li><a href="{{ route('about') }}">{{ __('About') }}</a></li>
                        <li><a href="{{ route('projects.index') }}">{{ __('Projects') }}</a></li>
                        <li><a href="{{ route('careers.index') }}">{{ __('Careers') }}</a></li>
                    @endforelse
                </ul>
            </div>

            <div>
                <div class="footer__heading">{{ __('Services') }}</div>
                <ul class="footer__links">
                    @foreach ($footerServices as $service)
                        <li><a href="{{ route('services.show', $service) }}">{{ $service->title }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <div class="footer__heading">{{ __('Newsletter') }}</div>
                <p style="font-size:0.85rem;margin-bottom:1.25rem;">{{ __('Project stories and industry insights, occasionally.') }}</p>
                <form method="POST" action="{{ route('newsletter.subscribe') }}">
                    @csrf
                    <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off">
                    <div class="footer__newsletter">
                        <input type="email" name="email" placeholder="{{ __('Email address') }}" required>
                        <button type="submit">{{ __('Join') }}</button>
                    </div>
                    @if (session('newsletter_status'))
                        <p style="font-size:0.8rem;color:var(--accent);margin-top:0.75rem;">{{ session('newsletter_status') }}</p>
                    @endif
                </form>

                <div class="footer__heading" style="margin-top:2rem;">{{ __('Contact') }}</div>
                <ul class="footer__links">
                    @if ($address)<li>{{ $address }}</li>@endif
                    @if ($phone)<li><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ __('UAN') }}: {{ $phone }}</a></li>@endif
                    @if ($mobile)<li><a href="tel:{{ preg_replace('/\s+/', '', $mobile) }}">{{ __('Mobile') }}: {{ $mobile }}</a></li>@endif
                    @if ($email)<li><a href="mailto:{{ $email }}">{{ $email }}</a></li>@endif
                </ul>
            </div>
        </div>

        <div class="footer__bottom">
            <div>&copy; {{ now()->year }} {{ $siteName }}. {{ __('All rights reserved.') }}</div>
            <div class="footer__legal">
                <a href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a>
                <a href="{{ route('terms') }}">{{ __('Terms') }}</a>
            </div>
        </div>
    </div>
</footer>

@if ($whatsapp = \App\Models\Setting::get('whatsapp_number'))
    <a href="https://wa.me/{{ preg_replace('/\D/', '', $whatsapp) }}" target="_blank" rel="noopener" class="whatsapp-fab" aria-label="{{ __('Chat on WhatsApp') }}">
        <x-icon name="whatsapp" />
    </a>
@endif
