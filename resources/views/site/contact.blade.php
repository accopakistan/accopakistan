@php use App\Models\Setting; @endphp

<x-site-layout :title="__('Contact Us')" :description="__('Get in touch with ACCO Pakistan for your next architecture, engineering, or construction project.')">
    <x-page-header
        :eyebrow="__('Get In Touch')"
        :title="__('Contact Us')"
        subtitle="Tell us about your project. Our team typically responds within one business day."
        :image="Setting::imageUrl('contact_header_image', 'https://picsum.photos/seed/acco-contact-header/1920/900')"
        :breadcrumbs="[__('Contact') => null]"
    />

    <section class="section--tight">
        <div class="container">
            <div class="contact-info">
                @if ($address = Setting::get('address'))
                    <div class="contact-info__item reveal-up">
                        <x-icon name="map-pin" />
                        <h3>{{ __('Head Office') }}</h3>
                        <p>{{ $address }}</p>
                    </div>
                @endif
                @if ($phone = Setting::get('phone'))
                    <div class="contact-info__item reveal-up">
                        <x-icon name="phone" />
                        <h3>{{ __('UAN') }}</h3>
                        <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                    </div>
                @endif
                @if ($mobile = Setting::get('mobile'))
                    <div class="contact-info__item reveal-up">
                        <x-icon name="smartphone" />
                        <h3>{{ __('Mobile') }}</h3>
                        <a href="tel:{{ preg_replace('/\s+/', '', $mobile) }}">{{ $mobile }}</a>
                    </div>
                @endif
                @if ($email = Setting::get('email'))
                    <div class="contact-info__item reveal-up">
                        <x-icon name="mail" />
                        <h3>{{ __('Email') }}</h3>
                        <a href="mailto:{{ $email }}">{{ $email }}</a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container row-editorial">
            <div class="reveal-up">
                <h2 class="display-2" style="margin-bottom:2rem;">{{ __('Send Us a Message') }}</h2>
                @include('site.partials.contact-form')
            </div>

            <div class="reveal-up contact-card">
                <h3 class="heading-sm" style="margin-bottom:1.5rem;">{{ __('Get in Touch') }}</h3>

                <dl style="margin:0;">
                    @if ($address)
                        <div class="fact-row"><dt>{{ __('Address') }}</dt><dd>{{ $address }}</dd></div>
                    @endif
                    @if ($phone)
                        <div class="fact-row"><dt>{{ __('UAN') }}</dt><dd><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a></dd></div>
                    @endif
                    @if ($mobile)
                        <div class="fact-row"><dt>{{ __('Mobile') }}</dt><dd><a href="tel:{{ preg_replace('/\s+/', '', $mobile) }}">{{ $mobile }}</a></dd></div>
                    @endif
                    @if ($email)
                        <div class="fact-row"><dt>{{ __('Email') }}</dt><dd><a href="mailto:{{ $email }}">{{ $email }}</a></dd></div>
                    @endif
                    @if ($hours = Setting::get('business_hours'))
                        <div class="fact-row"><dt>{{ __('Hours') }}</dt><dd>{{ $hours }}</dd></div>
                    @endif
                </dl>

                @if ($whatsapp = Setting::get('whatsapp_number'))
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $whatsapp) }}" target="_blank" rel="noopener" class="btn btn--gold" style="width:100%;justify-content:center;margin-top:2rem;">
                        <x-icon name="whatsapp" /> {{ __('Chat on WhatsApp') }}
                    </a>
                @endif
            </div>
        </div>
    </section>

    <section class="section--tight" style="padding-top:0;">
        <div class="container">
            <div class="eyebrow reveal-up">{{ __('Find Us') }}</div>
            <h2 class="display-2 reveal-up" style="margin:1rem 0 2rem;">{{ __('Our Location') }}</h2>

            @if ($mapEmbed = Setting::get('google_map_embed'))
                <div class="map-frame map-frame--wide reveal-up">{!! $mapEmbed !!}</div>
            @else
                <div class="map-frame map-frame--wide reveal-up" style="display:flex;align-items:center;justify-content:center;">
                    <span class="text-muted small">{{ __('Map will appear here once configured in Settings.') }}</span>
                </div>
            @endif
        </div>
    </section>
</x-site-layout>
