<x-site-layout :title="__('Terms & Conditions')">
    <x-page-header :title="__('Terms & Conditions')" :breadcrumbs="[__('Terms & Conditions') => null]" />

    <section class="section">
        <div class="container content-page prose">
            @foreach (explode("\n", \App\Models\Setting::get('terms_content', '')) as $paragraph)
                @if (trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif
            @endforeach
        </div>
    </section>
</x-site-layout>
