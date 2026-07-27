<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::published()
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $request->string('category'))))
            ->with('category')
            ->orderBy('order')
            ->paginate(9)
            ->withQueryString();

        return view('site.projects.index', [
            'projects' => $projects,
            'categories' => ProjectCategory::orderBy('name')->get(),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->status === 'published', 404);

        $project->load('seo', 'category');

        return view('site.projects.show', [
            'project' => $project,
            'relatedProjects' => Project::published()->where('id', '!=', $project->id)->inRandomOrder()->limit(3)->get(),
        ]);
    }
}
