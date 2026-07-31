<?php

namespace App\Services\AiAssistant\Tools;

use App\Models\BlogPost;
use App\Services\AiAssistant\ImageGenerationClient;
use Illuminate\Support\Str;

class BlogPostTools
{
    protected static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (BlogPost::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    protected static function summarize(BlogPost $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'status' => $post->status,
            'is_featured' => $post->is_featured,
            'published_at' => $post->published_at?->toDateString(),
            'blog_category_id' => $post->blog_category_id,
        ];
    }

    public static function definitions(): array
    {
        return [
            [
                'name' => 'list_blog_posts',
                'description' => 'List blog posts, optionally filtered by status. Returns id, title, slug, excerpt, status for each — use get_blog_post to fetch full content.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'status' => ['type' => 'string', 'description' => 'Filter by status: "draft" or "published". Omit for all.'],
                        'search' => ['type' => 'string', 'description' => 'Optional keyword to search in the title.'],
                        'limit' => ['type' => 'integer', 'description' => 'Max results, default 20.'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $query = BlogPost::query()->orderByDesc('id');

                    if (! empty($input['status'])) {
                        $query->where('status', $input['status']);
                    }

                    if (! empty($input['search'])) {
                        $query->where('title', 'like', '%'.$input['search'].'%');
                    }

                    return $query->limit($input['limit'] ?? 20)->get()
                        ->map(fn (BlogPost $p) => static::summarize($p))
                        ->all();
                },
            ],
            [
                'name' => 'get_blog_post',
                'description' => 'Fetch the full details (including full content body) of a single blog post by id or slug.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'slug' => ['type' => 'string'],
                    ],
                ],
                'handler' => function (array $input): array {
                    $post = ! empty($input['id'])
                        ? BlogPost::find($input['id'])
                        : BlogPost::where('slug', $input['slug'] ?? '')->first();

                    if (! $post) {
                        return ['error' => 'Blog post not found.'];
                    }

                    return array_merge(static::summarize($post), ['content' => $post->content]);
                },
            ],
            [
                'name' => 'create_blog_post',
                'description' => 'Create a new blog post. A URL slug is auto-generated from the title if not provided. Defaults to draft status.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string'],
                        'excerpt' => ['type' => 'string', 'description' => 'Short summary shown in listings.'],
                        'content' => ['type' => 'string', 'description' => 'Full HTML or plain-text article body.'],
                        'status' => ['type' => 'string', 'description' => '"draft" or "published". Defaults to "draft".'],
                        'is_featured' => ['type' => 'boolean'],
                    ],
                    'required' => ['title'],
                ],
                'handler' => function (array $input): array {
                    $post = BlogPost::create([
                        'title' => $input['title'],
                        'slug' => static::uniqueSlug($input['title']),
                        'excerpt' => $input['excerpt'] ?? null,
                        'content' => $input['content'] ?? null,
                        'status' => $input['status'] ?? 'draft',
                        'is_featured' => (bool) ($input['is_featured'] ?? false),
                        'published_at' => ($input['status'] ?? 'draft') === 'published' ? now() : null,
                    ]);

                    return static::summarize($post);
                },
            ],
            [
                'name' => 'update_blog_post',
                'description' => 'Update fields on an existing blog post by id. Only pass the fields you want to change.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'title' => ['type' => 'string'],
                        'excerpt' => ['type' => 'string'],
                        'content' => ['type' => 'string'],
                        'status' => ['type' => 'string', 'description' => '"draft" or "published".'],
                        'is_featured' => ['type' => 'boolean'],
                    ],
                    'required' => ['id'],
                ],
                'handler' => function (array $input): array {
                    $post = BlogPost::find($input['id'] ?? null);

                    if (! $post) {
                        return ['error' => 'Blog post not found.'];
                    }

                    $data = array_intersect_key($input, array_flip(['title', 'excerpt', 'content', 'status', 'is_featured']));

                    if (isset($data['status']) && $data['status'] === 'published' && ! $post->published_at) {
                        $data['published_at'] = now();
                    }

                    $post->update($data);

                    return static::summarize($post->refresh());
                },
            ],
            [
                'name' => 'update_blog_post_seo',
                'description' => 'Set the SEO meta title, meta description, and/or keywords for a blog post. Always use this tool for meta title/description/keywords — never write them as literal text inside the post content or excerpt.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'seo_title' => ['type' => 'string', 'description' => 'Meta title, ideally under 60 characters.'],
                        'seo_description' => ['type' => 'string', 'description' => 'Meta description, ideally under 160 characters.'],
                        'seo_keywords' => ['type' => 'string', 'description' => 'Comma-separated focus keywords.'],
                    ],
                    'required' => ['id'],
                ],
                'handler' => function (array $input): array {
                    $post = BlogPost::find($input['id'] ?? null);

                    if (! $post) {
                        return ['error' => 'Blog post not found.'];
                    }

                    $data = [];

                    if (array_key_exists('seo_title', $input)) {
                        $data['title'] = $input['seo_title'];
                    }

                    if (array_key_exists('seo_description', $input)) {
                        $data['description'] = $input['seo_description'];
                    }

                    if (array_key_exists('seo_keywords', $input)) {
                        $data['keywords'] = $input['seo_keywords'];
                    }

                    $post->saveSeo($data);

                    return ['success' => true, 'id' => $post->id, 'seo' => $data];
                },
            ],
            [
                'name' => 'generate_blog_post_image',
                'description' => 'Generate a featured image for a blog post from a text description and attach it to the post. Replaces any existing featured image. Write a specific, visual prompt (subject, setting, style) — not the blog post title.',
                'input_schema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'prompt' => [
                            'type' => 'string',
                            'description' => 'A specific visual description of the image to generate, e.g. "Photorealistic exterior of a modern hospital building in Pakistan at golden hour, architectural photography style."',
                        ],
                    ],
                    'required' => ['id', 'prompt'],
                ],
                'handler' => function (array $input): array {
                    $post = BlogPost::find($input['id'] ?? null);

                    if (! $post) {
                        return ['error' => 'Blog post not found.'];
                    }

                    if (empty($input['prompt'])) {
                        return ['error' => 'A prompt is required to generate an image.'];
                    }

                    $image = app(ImageGenerationClient::class)->generate($input['prompt']);

                    $extension = match ($image['mime_type']) {
                        'image/jpeg' => 'jpg',
                        'image/webp' => 'webp',
                        default => 'png',
                    };

                    $post->addMediaFromString($image['data'])
                        ->usingFileName("blog-{$post->id}-featured.{$extension}")
                        ->toMediaCollection('featured_image');

                    return [
                        'success' => true,
                        'id' => $post->id,
                        'featured_image_url' => $post->fresh()->featuredImageUrl(),
                    ];
                },
            ],
        ];
    }
}
