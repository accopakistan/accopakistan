<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\Setting;

class SettingsTools
{
    /**
     * Flatten config/site-settings.php into key => [group, type, label, default].
     */
    protected static function registry(): array
    {
        static $flat = null;

        if ($flat !== null) {
            return $flat;
        }

        $flat = [];

        foreach (config('site-settings', []) as $group => $fields) {
            foreach ($fields as $key => $meta) {
                $flat[$key] = [
                    'group' => $group,
                    'type' => $meta['type'] ?? 'string',
                    'label' => $meta['label'] ?? $key,
                    'default' => $meta['default'] ?? null,
                ];
            }
        }

        return $flat;
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_settings',
                'description' => 'List every editable site setting, optionally filtered by group. Each entry includes its key, group, label, type, and current value. For text/number type settings, use update_setting to modify. For image type settings, use generate_setting_image to regenerate.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'group' => [
                            'type' => 'string',
                            'description' => 'Optional group name to filter by, e.g. "general", "homepage", "about", "seo", "social". Omit to list all groups.',
                        ],
                    ],
                ],
                'handler' => function (array $input): array {
                    $rows = [];

                    foreach (static::registry() as $key => $meta) {
                        if (! empty($input['group']) && $meta['group'] !== $input['group']) {
                            continue;
                        }

                        $rows[] = [
                            'key' => $key,
                            'group' => $meta['group'],
                            'label' => $meta['label'],
                            'type' => $meta['type'],
                            'value' => Setting::get($key, $meta['default']),
                        ];
                    }

                    return $rows;
                },
            ],
            [
                'name' => 'update_setting',
                'description' => 'Update the value of one existing text/number site setting by its key (as returned by list_settings). Cannot be used for image-type settings (for those, use generate_setting_image).',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'key' => ['type' => 'string', 'description' => 'The setting key, e.g. "hero_heading".'],
                        'value' => ['type' => 'string', 'description' => 'The new value to store.'],
                    ],
                    'required' => ['key', 'value'],
                ],
                'handler' => function (array $input): array {
                    $registry = static::registry();
                    $key = $input['key'] ?? '';

                    if (! isset($registry[$key])) {
                        return ['error' => "Unknown setting key \"{$key}\". Call list_settings first to see valid keys."];
                    }

                    $meta = $registry[$key];

                    if ($meta['type'] === 'image') {
                        return ['error' => "\"{$key}\" is an image setting and cannot be updated through this text tool. Use generate_setting_image instead."];
                    }

                    Setting::set($key, (string) ($input['value'] ?? ''), $meta['group'], $meta['type']);

                    return ['success' => true, 'key' => $key, 'value' => $input['value']];
                },
            ],
            [
                'name' => 'generate_setting_image',
                'description' => 'Generate an image for an image-type site setting (like "hero_image", "about_image", "blog_header_image", "services_header_image", "projects_header_image", "about_header_image", "contact_header_image", "careers_header_image", "faqs_header_image") from a text description and save it. Replaces any existing image for that setting.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'key' => ['type' => 'string', 'description' => 'The image setting key, e.g. "hero_image", "about_image".'],
                        'prompt' => [
                            'type' => 'string',
                            'description' => 'A specific visual description of the image to generate, e.g. "A photorealistic wide-angle shot of a completed building facade, professional architectural photography."',
                        ],
                    ],
                    'required' => ['key', 'prompt'],
                ],
                'handler' => function (array $input): array {
                    $registry = static::registry();
                    $key = $input['key'] ?? '';

                    if (! isset($registry[$key])) {
                        return ['error' => "Unknown setting key \"{$key}\". Call list_settings first to see valid keys."];
                    }

                    $meta = $registry[$key];

                    if ($meta['type'] !== 'image') {
                        return ['error' => "\"{$key}\" is not an image setting. Use update_setting instead."];
                    }

                    if (empty($input['prompt'])) {
                        return ['error' => 'A prompt is required to generate an image.'];
                    }

                    $image = app(\App\Services\AiAssistant\ImageGenerationClient::class)->generate($input['prompt']);

                    $extension = match ($image['mime_type']) {
                        'image/jpeg' => 'jpg',
                        'image/webp' => 'webp',
                        default => 'png',
                    };

                    $existing = Setting::where('key', $key)->first();
                    if ($existing && $existing->value) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($existing->value);
                    }

                    $filename = 'settings/'.$key.'-'.uniqid().'.'.$extension;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $image['data']);

                    Setting::set($key, $filename, $meta['group'], 'image');

                    return [
                        'success' => true,
                        'key' => $key,
                        'value' => \Illuminate\Support\Facades\Storage::disk('public')->url($filename),
                    ];
                },
            ],
        ];
    }
}
