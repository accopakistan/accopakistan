<?php

namespace App\View\Components;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;
use Illuminate\View\View;

class SeoMeta extends Component
{
    public string $metaTitle;

    public string $metaDescription;

    public ?string $metaKeywords;

    public string $canonicalUrl;

    public ?string $ogImage;

    public ?array $schemaJson;

    public function __construct(
        public mixed $seoable = null,
        public ?string $title = null,
        public ?string $description = null,
    ) {
        $siteName = Setting::get('site_name', config('app.name'));
        $seo = $seoable?->seo;

        $this->metaTitle = $seo?->title
            ?: $this->title
            ?: ($seoable?->title ?? $seoable?->name ?? null)
            ?: $siteName;

        if (! str_contains($this->metaTitle, (string) $siteName)) {
            $this->metaTitle = "{$this->metaTitle} | {$siteName}";
        }

        $this->metaDescription = $seo?->description
            ?: $this->description
            ?: ($seoable?->excerpt ?? null)
            ?: Setting::get('default_meta_description', '')
            ?: '';

        $this->metaKeywords = $seo?->keywords;
        $this->canonicalUrl = $seo?->canonical_url ?: url()->current();

        $featuredImage = ($seoable !== null && method_exists($seoable, 'featuredImageUrl'))
            ? $seoable->featuredImageUrl()
            : null;

        $ogImage = $seo?->og_image ?: $featuredImage ?: Setting::get('default_og_image');

        $this->ogImage = $ogImage ? (str_starts_with($ogImage, 'http') ? $ogImage : Storage::disk('public')->url($ogImage)) : null;
        $this->schemaJson = $seo?->schema_json;
    }

    public function render(): View
    {
        return view('components.seo-meta');
    }
}
