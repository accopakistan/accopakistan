<x-admin-layout>
    <x-slot name="title">{{ __('SEO Tools') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('SEO Tools') }}</h1>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="icon-tile mb-3"><i class="bi bi-diagram-3"></i></div>
                    <h2 class="h5 fw-semibold">{{ __('XML Sitemap') }}</h2>
                    @if ($sitemapExists)
                        <p class="text-muted small mb-1">{{ __('Contains :count URLs.', ['count' => $sitemapUrlCount]) }}</p>
                        <p class="text-muted small">{{ __('Last generated :time.', ['time' => \Illuminate\Support\Carbon::createFromTimestamp($sitemapGeneratedAt)->diffForHumans()]) }}</p>
                        <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-sm btn-outline-secondary">{{ __('View Sitemap') }}</a>
                    @else
                        <p class="text-muted small">{{ __('No sitemap has been generated yet.') }}</p>
                    @endif

                    <form action="{{ route('admin.seo.sitemap.regenerate') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-arrow-clockwise"></i> {{ __('Regenerate Sitemap') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="icon-tile mb-3"><i class="bi bi-robot"></i></div>
                    <h2 class="h5 fw-semibold">{{ __('Robots.txt') }}</h2>
                    <p class="text-muted small">{{ __('Automatically generated from your settings.') }}</p>
                    <a href="{{ route('robots') }}" target="_blank" class="btn btn-sm btn-outline-secondary">{{ __('View robots.txt') }}</a>
                    <a href="{{ route('admin.settings.edit', 'seo') }}" class="btn btn-sm btn-outline-secondary">{{ __('Edit Rules') }}</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="icon-tile mb-3"><i class="bi bi-signpost-split"></i></div>
                    <h2 class="h5 fw-semibold">{{ __('Redirect Manager') }}</h2>
                    <p class="text-muted small">{{ __('Manage 301/302 redirects for moved or removed pages.') }}</p>
                    <a href="{{ route('admin.redirects.index') }}" class="btn btn-sm btn-primary">{{ __('Manage Redirects') }}</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="icon-tile mb-3"><i class="bi bi-exclamation-triangle"></i></div>
                    <h2 class="h5 fw-semibold">{{ __('404 Monitor') }}</h2>
                    <p class="text-muted small">{{ __('See which broken links visitors are hitting most often.') }}</p>
                    <a href="{{ route('admin.not-found-logs.index') }}" class="btn btn-sm btn-primary">{{ __('View 404s') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
