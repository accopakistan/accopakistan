<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'project_id',
        'client_name',
        'client_position',
        'company',
        'quote',
        'rating',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'rating' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function photoUrl(): ?string
    {
        return $this->getFirstMediaUrl('photo') ?: null;
    }
}
