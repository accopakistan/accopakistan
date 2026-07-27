<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlogPostController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::with('category', 'author')->latest()->paginate(15);
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();

        return view('admin.blog.index', compact('posts', 'categories', 'tags'));
    }

    public function create(): View
    {
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();

        return view('admin.blog.create', ['post' => new BlogPost, 'categories' => $categories, 'tags' => $tags]);
    }

    protected function rules(?BlogPost $post = null): array
    {
        return [
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_posts', 'slug')->ignore($post)],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:blog_tags,id'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function readingTime(?string $content): ?int
    {
        if (! $content) {
            return null;
        }

        return max(1, (int) ceil(str_word_count(strip_tags($content)) / 200));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        $post = BlogPost::create([
            ...collect($data)->except(['tags', 'featured_image', 'seo_title', 'seo_description'])->all(),
            'author_id' => $request->user()->id,
            'reading_time' => $this->readingTime($data['content'] ?? null),
        ]);

        $post->tags()->sync($data['tags'] ?? []);
        $this->syncMedia($request, $post, $data);

        return redirect()->route('admin.blog.edit', $post)->with('status', __('Post created successfully.'));
    }

    public function edit(BlogPost $post): View
    {
        $post->load('seo', 'tags');
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();

        return view('admin.blog.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, BlogPost $post): RedirectResponse
    {
        $data = $request->validate($this->rules($post));

        $post->update([
            ...collect($data)->except(['tags', 'featured_image', 'seo_title', 'seo_description'])->all(),
            'reading_time' => $this->readingTime($data['content'] ?? null),
        ]);

        $post->tags()->sync($data['tags'] ?? []);
        $this->syncMedia($request, $post, $data);

        return redirect()->route('admin.blog.edit', $post)->with('status', __('Post updated successfully.'));
    }

    protected function syncMedia(Request $request, BlogPost $post, array $data): void
    {
        if ($request->hasFile('featured_image')) {
            $post->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        $post->saveSeo([
            'title' => $data['seo_title'] ?? null,
            'description' => $data['seo_description'] ?? null,
        ]);
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.blog.index')->with('status', __('Post deleted successfully.'));
    }
}
