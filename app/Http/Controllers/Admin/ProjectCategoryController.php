<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectCategoryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255']]);

        ProjectCategory::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::random(4),
        ]);

        return back()->with('status', __('Category created successfully.'));
    }

    public function destroy(ProjectCategory $projectCategory): RedirectResponse
    {
        $projectCategory->delete();

        return back()->with('status', __('Category deleted successfully.'));
    }
}
