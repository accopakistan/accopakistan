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
                'description' => 'List every editable site setting (text/number fields only — images are excluded since they cannot be uploaded through chat), optionally filtered by group. Each entry includes its key, group, label, type, and current value. Use this to discover which settings exist before updating one.',
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
                        if ($meta['type'] === 'image') {
                            continue;
                        }

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
                'description' => 'Update the value of one existing text/number site setting by its key (as returned by list_settings). Cannot be used for image-type settings.',
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
                        return ['error' => "\"{$key}\" is an image setting and cannot be updated through chat. Ask the admin to upload it manually."];
                    }

                    Setting::set($key, (string) ($input['value'] ?? ''), $meta['group'], $meta['type']);

                    return ['success' => true, 'key' => $key, 'value' => $input['value']];
                },
            ],
        ];
    }
}
