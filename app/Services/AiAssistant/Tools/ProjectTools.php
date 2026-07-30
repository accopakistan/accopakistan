<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\Project;
use Illuminate\Support\Str;

class ProjectTools
{
    protected static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Project::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    protected static function summarize(Project $project): array
    {
        return [
            'id' => $project->id,
            'title' => $project->title,
            'slug' => $project->slug,
            'client' => $project->client,
            'location' => $project->location,
            'status' => $project->status,
            'is_featured' => $project->is_featured,
            'excerpt' => $project->excerpt,
        ];
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_projects',
                'description' => 'List portfolio projects (id, title, slug, client, location, status). Use get_project for full details of one.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'status' => ['type' => 'string', 'description' => 'Filter by "draft" or "published".'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $query = Project::query()->orderBy('order');

                    if (! empty($input['status'])) {
                        $query->where('status', $input['status']);
                    }

                    return $query->get()->map(fn (Project $p) => static::summarize($p))->all();
                },
            ],
            [
                'name' => 'get_project',
                'description' => 'Fetch full details of one portfolio project by id or slug, including scope, features, and content.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'slug' => ['type' => 'string'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $project = ! empty($input['id'])
                        ? Project::find($input['id'])
                        : Project::where('slug', $input['slug'] ?? '')->first();

                    if (! $project) {
                        return ['error' => 'Project not found.'];
                    }

                    return array_merge(static::summarize($project), [
                        'completion_date' => $project->completion_date?->toDateString(),
                        'project_value' => $project->project_value,
                        'area' => $project->area,
                        'scope' => $project->scope,
                        'features' => $project->features,
                        'content' => $project->content,
                    ]);
                },
            ],
            [
                'name' => 'create_project',
                'description' => 'Create a new portfolio project entry. Slug auto-generated from title if omitted. Defaults to draft status.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string'],
                        'client' => ['type' => 'string'],
                        'location' => ['type' => 'string'],
                        'project_value' => ['type' => 'string'],
                        'area' => ['type' => 'string'],
                        'scope' => ['type' => 'string'],
                        'features' => ['type' => 'string'],
                        'excerpt' => ['type' => 'string'],
                        'content' => ['type' => 'string'],
                        'status' => ['type' => 'string', 'description' => '"draft" or "published". Defaults to "draft".'],
                        'is_featured' => ['type' => 'boolean'],
                    ],
                    'required' => ['title'],
                ],
                'handler' => function (array $input): array {
                    $project = Project::create([
                        'title' => $input['title'],
                        'slug' => static::uniqueSlug($input['title']),
                        'client' => $input['client'] ?? null,
                        'location' => $input['location'] ?? null,
                        'project_value' => $input['project_value'] ?? null,
                        'area' => $input['area'] ?? null,
                        'scope' => $input['scope'] ?? null,
                        'features' => $input['features'] ?? null,
                        'excerpt' => $input['excerpt'] ?? null,
                        'content' => $input['content'] ?? null,
                        'status' => $input['status'] ?? 'draft',
                        'is_featured' => (bool) ($input['is_featured'] ?? false),
                        'order' => (Project::max('order') ?? 0) + 1,
                    ]);

                    return static::summarize($project);
                },
            ],
            [
                'name' => 'update_project',
                'description' => 'Update fields on an existing portfolio project by id. Only pass the fields you want to change.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'title' => ['type' => 'string'],
                        'client' => ['type' => 'string'],
                        'location' => ['type' => 'string'],
                        'project_value' => ['type' => 'string'],
                        'area' => ['type' => 'string'],
                        'scope' => ['type' => 'string'],
                        'features' => ['type' => 'string'],
                        'excerpt' => ['type' => 'string'],
                        'content' => ['type' => 'string'],
                        'status' => ['type' => 'string'],
                        'is_featured' => ['type' => 'boolean'],
                    ],
                    'required' => ['id'],
                ],
                'handler' => function (array $input): array {
                    $project = Project::find($input['id'] ?? null);

                    if (! $project) {
                        return ['error' => 'Project not found.'];
                    }

                    $data = array_intersect_key($input, array_flip([
                        'title', 'client', 'location', 'project_value', 'area',
                        'scope', 'features', 'excerpt', 'content', 'status', 'is_featured',
                    ]));

                    $project->update($data);

                    return static::summarize($project->refresh());
                },
            ],
        ];
    }
}
