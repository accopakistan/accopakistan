<?php

namespace App\Models;

use App\Traits\HasActivityLog;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosting extends Model
{
    use HasActivityLog, HasSeo, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'type',
        'description',
        'requirements',
        'status',
        'closing_date',
    ];

    protected function casts(): array
    {
        return [
            'closing_date' => 'date',
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
