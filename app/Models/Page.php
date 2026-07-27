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

class Page extends Model implements HasMedia
{
    use HasActivityLog, HasSeo, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'template',
        'excerpt',
        'content',
        'status',
        'is_homepage',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_homepage' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)->orderBy('order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
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
