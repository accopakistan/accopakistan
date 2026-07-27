<?php

namespace App\Models;

use App\Traits\HasActivityLog;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use HasActivityLog, HasSeo, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'project_category_id',
        'title',
        'slug',
        'client',
        'location',
        'completion_date',
        'project_value',
        'area',
        'scope',
        'features',
        'milestones',
        'services_involved',
        'excerpt',
        'content',
        'status',
        'is_featured',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'completion_date' => 'date',
            'milestones' => 'array',
            'services_involved' => 'array',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
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
