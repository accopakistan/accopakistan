<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\Download;

class DownloadTools
{
    protected static function summarize(Download $download): array
    {
        return [
            'id' => $download->id,
            'title' => $download->title,
            'description' => $download->description,
            'category' => $download->category,
            'file_size' => $download->file_size,
            'download_count' => $download->download_count,
            'order' => $download->order,
            'is_active' => $download->is_active,
            'file_url' => $download->fileUrl(),
        ];
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_downloads',
                'description' => 'List all downloadable publication files, brochures, and technical documents.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'category' => ['type' => 'string', 'description' => 'Filter by category, e.g. "Brochure", "Corporate", "Technical".'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $query = Download::query()->orderBy('order');

                    if (! empty($input['category'])) {
                        $query->where('category', $input['category']);
                    }

                    return $query->get()->map(fn (Download $d) => static::summarize($d))->all();
                },
            ],
            [
                'name' => 'create_download',
                'description' => 'Create a new download attachment record. Note: the physical file itself cannot be uploaded via chat and must be attached manually by the administrator in the Downloads admin panel page.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string'],
                        'description' => ['type' => 'string'],
                        'category' => ['type' => 'string'],
                        'file_size' => ['type' => 'string', 'description' => 'Estimated size, e.g. "2.4 MB".'],
                        'is_active' => ['type' => 'boolean'],
                    ],
                    'required' => ['title'],
                ],
                'handler' => function (array $input): array {
                    $download = Download::create([
                        'title' => $input['title'],
                        'description' => $input['description'] ?? null,
                        'category' => $input['category'] ?? null,
                        'file_size' => $input['file_size'] ?? null,
                        'download_count' => 0,
                        'is_active' => (bool) ($input['is_active'] ?? true),
                        'order' => (Download::max('order') ?? 0) + 1,
                    ]);

                    return static::summarize($download);
                },
            ],
            [
                'name' => 'update_download',
                'description' => 'Update fields on an existing download by id. Only pass the fields you want to change.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'title' => ['type' => 'string'],
                        'description' => ['type' => 'string'],
                        'category' => ['type' => 'string'],
                        'file_size' => ['type' => 'string'],
                        'is_active' => ['type' => 'boolean'],
                    ],
                    'required' => ['id'],
                ],
                'handler' => function (array $input): array {
                    $download = Download::find($input['id'] ?? null);

                    if (! $download) {
                        return ['error' => 'Download not found.'];
                    }

                    $data = array_intersect_key($input, array_flip([
                        'title', 'description', 'category', 'file_size', 'is_active'
                    ]));
                    $download->update($data);

                    return static::summarize($download->refresh());
                },
            ],
        ];
    }
}
