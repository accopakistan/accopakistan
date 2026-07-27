@php
    $seo = $post->seo ?? null;
    $selectedTags = old('tags', $post->tags?->pluck('id')->all() ?? []);
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" :value="old('title', $post->title ?? '')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" />
                    </div>
                    <div class="col-md-4">
                        <x-input-label for="blog_category_id" :value="__('Category')" />
                        <select id="blog_category_id" name="blog_category_id" class="form-select">
                            <option value="">{{ __('None') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('blog_category_id', $post->blog_category_id ?? '') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <x-input-label for="slug" :value="__('Slug')" />
                    <x-text-input id="slug" name="slug" type="text" :value="old('slug', $post->slug ?? '')" required />
                    <x-input-error :messages="$errors->get('slug')" />
                </div>

                <div class="mb-3">
                    <x-input-label :value="__('Tags')" />
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($tags as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}" @checked(in_array($tag->id, $selectedTags))>
                                <label class="form-check-label small" for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <x-input-label for="excerpt" :value="__('Excerpt')" />
                    <textarea id="excerpt" name="excerpt" class="form-control" rows="2">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                </div>
                <div class="mb-0">
                    <x-input-label for="content" :value="__('Content')" />
                    <textarea id="content" name="content" class="form-control" rows="12">{{ old('content', $post->content ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold">{{ __('SEO') }}</div>
            <div class="card-body">
                <div class="mb-3">
                    <x-input-label for="seo_title" :value="__('SEO Title')" />
                    <x-text-input id="seo_title" name="seo_title" type="text" :value="old('seo_title', $seo->title ?? '')" />
                </div>
                <div class="mb-0">
                    <x-input-label for="seo_description" :value="__('Meta Description')" />
                    <textarea id="seo_description" name="seo_description" class="form-control" rows="2">{{ old('seo_description', $seo->description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" class="form-select">
                        <option value="draft" @selected(old('status', $post->status ?? 'draft') === 'draft')>{{ __('Draft') }}</option>
                        <option value="published" @selected(old('status', $post->status ?? 'draft') === 'published')>{{ __('Published') }}</option>
                    </select>
                </div>
                <div class="mb-3">
                    <x-input-label for="published_at" :value="__('Publish Date')" />
                    <x-text-input id="published_at" name="published_at" type="datetime-local"
                        :value="old('published_at', isset($post->published_at) ? $post->published_at?->format('Y-m-d\TH:i') : '')" />
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $post->is_featured ?? false))>
                    <label class="form-check-label" for="is_featured">{{ __('Featured') }}</label>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <x-input-label for="featured_image" :value="__('Featured Image')" />
                @if (isset($post) && $post->featuredImageUrl())
                    <img src="{{ $post->featuredImageUrl() }}" class="img-fluid rounded mb-2 border">
                @endif
                <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*">
                <x-input-error :messages="$errors->get('featured_image')" />
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <x-primary-button>{{ __('Save Post') }}</x-primary-button>
    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
</div>
