<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings.all'));
        static::deleted(fn () => Cache::forget('settings.all'));
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever('settings.all', function () {
            return static::all()->keyBy('key');
        });

        if (! $settings->has($key)) {
            return $default;
        }

        return static::castValue($settings->get($key));
    }

    /**
     * URL for an image-type setting, or a fallback if it isn't set.
     */
    public static function imageUrl(string $key, string $fallback): string
    {
        $path = static::get($key);

        return $path ? Storage::disk('public')->url($path) : $fallback;
    }

    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string'): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value, 'group' => $group, 'type' => $type]
        );
    }

    protected static function castValue(self $setting): mixed
    {
        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }
}
