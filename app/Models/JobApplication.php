<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class JobApplication extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'job_posting_id',
        'name',
        'email',
        'phone',
        'cover_letter',
        'status',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('resume')->singleFile();
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function resumeUrl(): ?string
    {
        return $this->getFirstMediaUrl('resume') ?: null;
    }
}
