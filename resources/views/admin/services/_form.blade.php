@php $seo = $service->seo ?? null; @endphp

<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" type="text" :value="old('title', $service->title ?? '')" required autofocus />
                    <x-input-error :messages="$errors->get('title')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="slug" :value="__('Slug')" />
                    <x-text-input id="slug" name="slug" type="text" :value="old('slug', $service->slug ?? '')" required />
                    <x-input-error :messages="$errors->get('slug')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="hero_tagline" :value="__('Hero Tagline')" />
                    <x-text-input id="hero_tagline" name="hero_tagline" type="text" :value="old('hero_tagline', $service->hero_tagline ?? '')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="excerpt" :value="__('Excerpt')" />
                    <textarea id="excerpt" name="excerpt" class="form-control" rows="2">{{ old('excerpt', $service->excerpt ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get('excerpt')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="content" :value="__('Content (HTML — supports h2/h3/p/ul/a/table)')" />
                    <textarea id="content" name="content" class="form-control" rows="16" style="font-family:monospace;font-size:0.85rem;">{{ old('content', $service->content ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get('content')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="benefits" :value="__('Benefits (one per line, as: Title | Description)')" />
                    <textarea id="benefits" name="benefits" class="form-control" rows="5">{{ old('benefits', collect($service->benefits ?? [])->map(fn ($b) => ($b['title'] ?? '').' | '.($b['description'] ?? ''))->implode("\n")) }}</textarea>
                    <x-input-error :messages="$errors->get('benefits')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="process_steps" :value="__('Process Steps (one per line, as: Title | Description)')" />
                    <textarea id="process_steps" name="process_steps" class="form-control" rows="5">{{ old('process_steps', collect($service->process_steps ?? [])->map(fn ($p) => ($p['title'] ?? '').' | '.($p['description'] ?? ''))->implode("\n")) }}</textarea>
                    <x-input-error :messages="$errors->get('process_steps')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="comparison_table" :value="__('Comparison Table (JSON: {\"title\":\"\",\"headers\":[],\"rows\":[[]]})')" />
                    <textarea id="comparison_table" name="comparison_table" class="form-control" rows="6" style="font-family:monospace;font-size:0.8rem;">{{ old('comparison_table', $service->comparison_table ? json_encode($service->comparison_table) : '') }}</textarea>
                    <x-input-error :messages="$errors->get('comparison_table')" />
                </div>
                <div class="mb-0">
                    <x-input-label for="faqs" :value="__('FAQs (one per line, as: Question | Answer)')" />
                    <textarea id="faqs" name="faqs" class="form-control" rows="6">{{ old('faqs', collect($service->faqs ?? [])->map(fn ($f) => ($f['question'] ?? '').' | '.($f['answer'] ?? ''))->implode("\n")) }}</textarea>
                    <x-input-error :messages="$errors->get('faqs')" />
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold">{{ __('SEO') }}</div>
            <div class="card-body">
                <div class="mb-3">
                    <x-input-label for="seo_title" :value="__('Meta Title')" />
                    <x-text-input id="seo_title" name="seo_title" type="text" :value="old('seo_title', $seo->title ?? '')" />
                </div>
                <div class="mb-3">
                    <x-input-label for="seo_description" :value="__('Meta Description')" />
                    <textarea id="seo_description" name="seo_description" class="form-control" rows="2">{{ old('seo_description', $seo->description ?? '') }}</textarea>
                </div>
                <div class="mb-0">
                    <x-input-label for="seo_keywords" :value="__('Focus Keyphrase & Keywords (comma separated)')" />
                    <x-text-input id="seo_keywords" name="seo_keywords" type="text" :value="old('seo_keywords', $seo->keywords ?? '')" />
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
                        <option value="draft" @selected(old('status', $service->status ?? 'draft') === 'draft')>{{ __('Draft') }}</option>
                        <option value="published" @selected(old('status', $service->status ?? 'draft') === 'published')>{{ __('Published') }}</option>
                    </select>
                </div>
                <div class="mb-3">
                    <x-input-label for="icon" :value="__('Icon Class')" />
                    <x-text-input id="icon" name="icon" type="text" :value="old('icon', $service->icon ?? '')" placeholder="bi bi-building" />
                </div>
                <div class="mb-3">
                    <x-input-label for="order" :value="__('Order')" />
                    <x-text-input id="order" name="order" type="number" :value="old('order', $service->order ?? 0)" />
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $service->is_featured ?? false))>
                    <label class="form-check-label" for="is_featured">{{ __('Featured') }}</label>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <x-input-label for="featured_image" :value="__('Featured Image')" />
                @if (isset($service) && $service->featuredImageUrl())
                    <img src="{{ $service->featuredImageUrl() }}" class="img-fluid rounded mb-2 border">
                @endif
                <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*">
                <x-input-error :messages="$errors->get('featured_image')" />
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <x-primary-button>{{ __('Save Service') }}</x-primary-button>
    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
</div>
