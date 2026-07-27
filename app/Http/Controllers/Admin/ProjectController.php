<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('category')->orderBy('order')->paginate(15);
        $categories = ProjectCategory::orderBy('name')->get();

        return view('admin.projects.index', compact('projects', 'categories'));
    }

    public function create(): View
    {
        $categories = ProjectCategory::orderBy('name')->get();

        return view('admin.projects.create', ['project' => new Project, 'categories' => $categories]);
    }

    protected function rules(?Project $project = null): array
    {
        return [
            'project_category_id' => ['nullable', 'exists:project_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($project)],
            'client' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'completion_date' => ['nullable', 'date'],
            'project_value' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'scope' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'milestones' => ['nullable', 'string'],
            'services_involved' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'is_featured' => ['boolean'],
            'order' => ['nullable', 'integer'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'gallery.*' => ['nullable', 'image', 'max:5120'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function except(): array
    {
        return ['featured_image', 'gallery', 'seo_title', 'seo_description'];
    }

    protected function preparePayload(array $data): array
    {
        $data['milestones'] = collect(explode("\n", $data['milestones'] ?? ''))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function ($line) {
                [$title, $date] = array_pad(explode('|', $line, 2), 2, '');

                return ['title' => trim($title), 'date' => trim($date)];
            })
            ->values()
            ->all();

        $data['services_involved'] = collect(explode("\n", $data['services_involved'] ?? ''))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();

        return $data;
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->preparePayload($request->validate($this->rules()));

        $project = Project::create(collect($data)->except($this->except())->all());

        $this->syncMedia($request, $project);

        return redirect()->route('admin.projects.edit', $project)->with('status', __('Project created successfully.'));
    }

    public function edit(Project $project): View
    {
        $project->load('seo', 'media');
        $categories = ProjectCategory::orderBy('name')->get();

        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->preparePayload($request->validate($this->rules($project)));

        $project->update(collect($data)->except($this->except())->all());

        $this->syncMedia($request, $project);

        return redirect()->route('admin.projects.edit', $project)->with('status', __('Project updated successfully.'));
    }

    protected function syncMedia(Request $request, Project $project): void
    {
        if ($request->hasFile('featured_image')) {
            $project->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $project->addMedia($file)->toMediaCollection('gallery');
            }
        }

        $data = $request->only(['seo_title', 'seo_description']);

        $project->saveSeo([
            'title' => $data['seo_title'] ?? null,
            'description' => $data['seo_description'] ?? null,
        ]);
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', __('Project deleted successfully.'));
    }

    public function destroyGalleryImage(Project $project, int $mediaId): RedirectResponse
    {
        $project->media()->where('id', $mediaId)->where('collection_name', 'gallery')->firstOrFail()->delete();

        return back()->with('status', __('Image removed.'));
    }
}
