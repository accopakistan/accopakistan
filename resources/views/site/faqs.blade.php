<x-site-layout :title="__('Frequently Asked Questions')">
    <x-page-header
        :eyebrow="__('Help Center')"
        :title="__('Frequently Asked Questions')"
        :image="\App\Models\Setting::imageUrl('faqs_header_image', 'https://picsum.photos/seed/acco-faq-header/1920/900')"
        :breadcrumbs="[__('FAQs') => null]"
    />

    <section class="section">
        <div class="container container--narrow">
            @if ($faqs->isEmpty())
                <p class="text-muted">{{ __('FAQs will be added here soon.') }}</p>
            @else
                @foreach ($faqs as $category => $categoryFaqs)
                    <h2 class="heading-sm reveal-up" style="margin-bottom:1rem;">{{ $category }}</h2>
                    <div data-accordion-group class="reveal-up" style="margin-bottom:3rem;">
                        @foreach ($categoryFaqs as $faq)
                            <div class="accordion-item">
                                <button type="button" class="accordion-trigger" data-accordion-trigger aria-expanded="false">
                                    {{ $faq->question }} <x-icon name="plus" />
                                </button>
                                <div class="accordion-panel">
                                    <div class="accordion-panel__inner">{{ $faq->answer }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
        </div>
    </section>
</x-site-layout>
