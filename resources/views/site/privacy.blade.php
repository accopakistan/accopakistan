<x-site-layout :title="__('Privacy Policy')">
    <x-page-header :title="__('Privacy Policy')" :breadcrumbs="[__('Privacy Policy') => null]" />

    <section class="section">
        <div class="container content-page prose">
            @foreach (explode("\n", \App\Models\Setting::get('privacy_policy_content', '')) as $paragraph)
                @if (trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif
            @endforeach
        </div>
    </section>
</x-site-layout>
