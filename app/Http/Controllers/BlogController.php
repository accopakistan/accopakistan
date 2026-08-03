<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $posts = BlogPost::published()
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $request->string('category'))))
            ->when($request->filled('tag'), fn ($q) => $q->whereHas('tags', fn ($t) => $t->where('slug', $request->string('tag'))))
            ->with('category', 'author')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('site.blog.index', [
            'posts' => $posts,
            'categories' => BlogCategory::orderBy('name')->get(),
            'tags' => BlogTag::orderBy('name')->get(),
        ]);
    }

    public function show(BlogPost $post): View
    {
        abort_unless(
            $post->status === 'published' && ($post->published_at === null || $post->published_at->lte(now())),
            404
        );

        $post->load('seo', 'category', 'author', 'tags');

        return view('site.blog.show', [
            'post' => $post,
            'relatedPosts' => BlogPost::published()
                ->where('id', '!=', $post->id)
                ->when($post->blog_category_id, fn ($q) => $q->where('blog_category_id', $post->blog_category_id))
                ->limit(3)
                ->get(),
        ]);
    }
}
