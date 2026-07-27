<?php

namespace App\Models;

use App\Traits\HasActivityLog;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasActivityLog, HasSeo, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'excerpt',
        'hero_tagline',
        'content',
        'benefits',
        'process_steps',
        'comparison_table',
        'faqs',
        'status',
        'is_featured',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'benefits' => 'array',
            'process_steps' => 'array',
            'comparison_table' => 'array',
            'faqs' => 'array',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function featuredImageUrl(): ?string
    {
        return $this->getFirstMediaUrl('featured_image') ?: null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
