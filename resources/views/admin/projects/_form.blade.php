@php $seo = $project->seo ?? null; @endphp

<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" :value="old('title', $project->title ?? '')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" />
                    </div>
                    <div class="col-md-4">
                        <x-input-label for="project_category_id" :value="__('Category')" />
                        <select id="project_category_id" name="project_category_id" class="form-select">
                            <option value="">{{ __('None') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('project_category_id', $project->project_category_id ?? '') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <x-input-label for="slug" :value="__('Slug')" />
                    <x-text-input id="slug" name="slug" type="text" :value="old('slug', $project->slug ?? '')" required />
                    <x-input-error :messages="$errors->get('slug')" />
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <x-input-label for="client" :value="__('Client')" />
                        <x-text-input id="client" name="client" type="text" :value="old('client', $project->client ?? '')" />
                    </div>
                    <div class="col-md-4">
                        <x-input-label for="location" :value="__('Location')" />
                        <x-text-input id="location" name="location" type="text" :value="old('location', $project->location ?? '')" />
                    </div>
                    <div class="col-md-4">
                        <x-input-label for="completion_date" :value="__('Completion Date')" />
                        <x-text-input id="completion_date" name="completion_date" type="date" :value="old('completion_date', isset($project->completion_date) ? $project->completion_date?->format('Y-m-d') : '')" />
                    </div>
                    <div class="col-md-6">
                        <x-input-label for="project_value" :value="__('Project Value')" />
                        <x-text-input id="project_value" name="project_value" type="text" :value="old('project_value', $project->project_value ?? '')" placeholder="e.g. PKR 500 Million" />
                    </div>
                    <div class="col-md-6">
                        <x-input-label for="area" :value="__('Area')" />
                        <x-text-input id="area" name="area" type="text" :value="old('area', $project->area ?? '')" placeholder="e.g. 50,000 sq ft" />
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <x-input-label for="excerpt" :value="__('Excerpt')" />
                    <textarea id="excerpt" name="excerpt" class="form-control" rows="2">{{ old('excerpt', $project->excerpt ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <x-input-label for="scope" :value="__('Scope of Work')" />
                    <textarea id="scope" name="scope" class="form-control" rows="3">{{ old('scope', $project->scope ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <x-input-label for="features" :value="__('Key Features')" />
                    <textarea id="features" name="features" class="form-control" rows="3">{{ old('features', $project->features ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <x-input-label for="content" :value="__('Full Description')" />
                    <textarea id="content" name="content" class="form-control" rows="8">{{ old('content', $project->content ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <x-input-label for="milestones" :value="__('Timeline Milestones (one per line, as: Phase | Date)')" />
                    <textarea id="milestones" name="milestones" class="form-control" rows="4">{{ old('milestones', collect($project->milestones ?? [])->map(fn ($m) => ($m['title'] ?? '').' | '.($m['date'] ?? ''))->implode("\n")) }}</textarea>
                </div>
                <div class="mb-0">
                    <x-input-label for="services_involved" :value="__('Services Involved (one per line)')" />
                    <textarea id="services_involved" name="services_involved" class="form-control" rows="3">{{ old('services_involved', collect($project->services_involved ?? [])->implode("\n")) }}</textarea>
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
                        <option value="draft" @selected(old('status', $project->status ?? 'draft') === 'draft')>{{ __('Draft') }}</option>
                        <option value="published" @selected(old('status', $project->status ?? 'draft') === 'published')>{{ __('Published') }}</option>
                    </select>
                </div>
                <div class="mb-3">
                    <x-input-label for="order" :value="__('Order')" />
                    <x-text-input id="order" name="order" type="number" :value="old('order', $project->order ?? 0)" />
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $project->is_featured ?? false))>
                    <label class="form-check-label" for="is_featured">{{ __('Featured') }}</label>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <x-input-label for="featured_image" :value="__('Featured Image')" />
                @if (isset($project) && $project->featuredImageUrl())
                    <img src="{{ $project->featuredImageUrl() }}" class="img-fluid rounded mb-2 border">
                @endif
                <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*">
                <x-input-error :messages="$errors->get('featured_image')" />
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <x-input-label for="gallery" :value="__('Gallery Images')" />

                @if (isset($project) && $project->exists && $project->getMedia('gallery')->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @foreach ($project->getMedia('gallery') as $media)
                            <div class="position-relative">
                                <img src="{{ $media->getUrl() }}" style="width: 4rem; height: 4rem; object-fit: cover;" class="rounded border">
                                <form action="{{ route('admin.projects.gallery.destroy', [$project, $media->id]) }}" method="POST" class="position-absolute top-0 end-0">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger py-0 px-1" style="font-size: 0.65rem;">&times;</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

                <input type="file" id="gallery" name="gallery[]" class="form-control" accept="image/*" multiple>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <x-primary-button>{{ __('Save Project') }}</x-primary-button>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
</div>
