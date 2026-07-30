<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\Service;
use Illuminate\Support\Str;

class ServiceTools
{
    protected static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Service::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    protected static function summarize(Service $service): array
    {
        return [
            'id' => $service->id,
            'title' => $service->title,
            'slug' => $service->slug,
            'excerpt' => $service->excerpt,
            'status' => $service->status,
            'is_featured' => $service->is_featured,
            'order' => $service->order,
        ];
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_services',
                'description' => 'List all services offered on the site (id, title, slug, excerpt, status). Use get_service for full details of one.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'status' => ['type' => 'string', 'description' => 'Filter by "draft" or "published".'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $query = Service::query()->orderBy('order');

                    if (! empty($input['status'])) {
                        $query->where('status', $input['status']);
                    }

                    return $query->get()->map(fn (Service $s) => static::summarize($s))->all();
                },
            ],
            [
                'name' => 'get_service',
                'description' => 'Fetch full details of one service by id or slug, including content, benefits, process steps, comparison table, and per-service FAQs.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'slug' => ['type' => 'string'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $service = ! empty($input['id'])
                        ? Service::find($input['id'])
                        : Service::where('slug', $input['slug'] ?? '')->first();

                    if (! $service) {
                        return ['error' => 'Service not found.'];
                    }

                    return array_merge(static::summarize($service), [
                        'content' => $service->content,
                        'benefits' => $service->benefits,
                        'process_steps' => $service->process_steps,
                        'comparison_table' => $service->comparison_table,
                        'faqs' => $service->faqs,
                    ]);
                },
            ],
            [
                'name' => 'create_service',
                'description' => 'Create a new service offering. Slug auto-generated from title if omitted. Defaults to draft status.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string'],
                        'excerpt' => ['type' => 'string'],
                        'content' => ['type' => 'string', 'description' => 'Full HTML body describing the service.'],
                        'status' => ['type' => 'string', 'description' => '"draft" or "published". Defaults to "draft".'],
                        'is_featured' => ['type' => 'boolean'],
                    ],
                    'required' => ['title'],
                ],
                'handler' => function (array $input): array {
                    $service = Service::create([
                        'title' => $input['title'],
                        'slug' => static::uniqueSlug($input['title']),
                        'excerpt' => $input['excerpt'] ?? null,
                        'content' => $input['content'] ?? null,
                        'status' => $input['status'] ?? 'draft',
                        'is_featured' => (bool) ($input['is_featured'] ?? false),
                        'order' => (Service::max('order') ?? 0) + 1,
                    ]);

                    return static::summarize($service);
                },
            ],
            [
                'name' => 'update_service',
                'description' => 'Update fields on an existing service by id. Only pass the fields you want to change. benefits/process_steps must each be an array of {title, description} objects; comparison_table an array of {feature, acco, others} rows; faqs an array of {question, answer} objects.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'title' => ['type' => 'string'],
                        'excerpt' => ['type' => 'string'],
                        'content' => ['type' => 'string'],
                        'status' => ['type' => 'string'],
                        'is_featured' => ['type' => 'boolean'],
                        'benefits' => ['type' => 'array', 'items' => ['type' => 'object']],
                        'process_steps' => ['type' => 'array', 'items' => ['type' => 'object']],
                        'comparison_table' => ['type' => 'array', 'items' => ['type' => 'object']],
                        'faqs' => ['type' => 'array', 'items' => ['type' => 'object']],
                    ],
                    'required' => ['id'],
                ],
                'handler' => function (array $input): array {
                    $service = Service::find($input['id'] ?? null);

                    if (! $service) {
                        return ['error' => 'Service not found.'];
                    }

                    $data = array_intersect_key($input, array_flip([
                        'title', 'excerpt', 'content', 'status', 'is_featured',
                        'benefits', 'process_steps', 'comparison_table', 'faqs',
                    ]));

                    $service->update($data);

                    return static::summarize($service->refresh());
                },
            ],
        ];
    }
}
