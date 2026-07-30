<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAssistantMessage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'role',
        'content',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $message) {
            $message->created_at ??= now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
