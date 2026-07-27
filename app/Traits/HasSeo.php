<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function getSeoTitle(): string
    {
        return $this->seo?->title ?: ($this->title ?? $this->name ?? config('app.name'));
    }

    public function getSeoDescription(): string
    {
        return $this->seo?->description ?: ($this->excerpt ?? '');
    }

    public function saveSeo(array $attributes): SeoMeta
    {
        return $this->seo()->updateOrCreate([], $attributes);
    }
}
