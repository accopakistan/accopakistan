@php
    $seo = $page->seo ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" type="text" :value="old('title', $page->title ?? '')" required autofocus />
                    <x-input-error :messages="$errors->get('title')" />
                </div>

                <div class="mb-3">
                    <x-input-label for="slug" :value="__('Slug')" />
                    <x-text-input id="slug" name="slug" type="text" :value="old('slug', $page->slug ?? '')" required />
                    <x-input-error :messages="$errors->get('slug')" />
                </div>

                <div class="mb-3">
                    <x-input-label for="excerpt" :value="__('Excerpt')" />
                    <textarea id="excerpt" name="excerpt" class="form-control" rows="3">{{ old('excerpt', $page->excerpt ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get('excerpt')" />
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold">{{ __('SEO') }}</div>
            <div class="card-body">
                <div class="mb-3">
                    <x-input-label for="seo_title" :value="__('SEO Title')" />
                    <x-text-input id="seo_title" name="seo_title" type="text" :value="old('seo_title', $seo->title ?? '')" />
                    <x-input-error :messages="$errors->get('seo_title')" />
                </div>

                <div class="mb-3">
                    <x-input-label for="seo_description" :value="__('Meta Description')" />
                    <textarea id="seo_description" name="seo_description" class="form-control" rows="2">{{ old('seo_description', $seo->description ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get('seo_description')" />
                </div>

                <div class="mb-0">
                    <x-input-label for="seo_keywords" :value="__('Keywords')" />
                    <x-text-input id="seo_keywords" name="seo_keywords" type="text" :value="old('seo_keywords', $seo->keywords ?? '')" />
                    <x-input-error :messages="$errors->get('seo_keywords')" />
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
                        <option value="draft" @selected(old('status', $page->status ?? 'draft') === 'draft')>{{ __('Draft') }}</option>
                        <option value="published" @selected(old('status', $page->status ?? 'draft') === 'published')>{{ __('Published') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" />
                </div>

                <div class="mb-3">
                    <x-input-label for="template" :value="__('Template')" />
                    <select id="template" name="template" class="form-select">
                        <option value="default" @selected(old('template', $page->template ?? 'default') === 'default')>{{ __('Default') }}</option>
                        <option value="full-width" @selected(old('template', $page->template ?? 'default') === 'full-width')>{{ __('Full Width') }}</option>
                        <option value="landing" @selected(old('template', $page->template ?? 'default') === 'landing')>{{ __('Landing Page') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('template')" />
                </div>

                <div class="mb-3">
                    <x-input-label for="published_at" :value="__('Publish Date')" />
                    <x-text-input id="published_at" name="published_at" type="datetime-local"
                        :value="old('published_at', isset($page->published_at) ? $page->published_at?->format('Y-m-d\TH:i') : '')" />
                    <x-input-error :messages="$errors->get('published_at')" />
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_homepage" id="is_homepage" value="1" @checked(old('is_homepage', $page->is_homepage ?? false))>
                    <label class="form-check-label" for="is_homepage">{{ __('Set as homepage') }}</label>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <x-input-label for="featured_image" :value="__('Featured Image')" />

                @if (isset($page) && $page->featuredImageUrl())
                    <img src="{{ $page->featuredImageUrl() }}" class="img-fluid rounded mb-2 border">
                @endif

                <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*">
                <x-input-error :messages="$errors->get('featured_image')" />
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <x-primary-button>{{ __('Save Page') }}</x-primary-button>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
</div>
