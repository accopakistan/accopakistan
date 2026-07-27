<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogTagController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255']]);

        BlogTag::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::random(4),
        ]);

        return back()->with('status', __('Tag created successfully.'));
    }

    public function destroy(BlogTag $blogTag): RedirectResponse
    {
        $blogTag->delete();

        return back()->with('status', __('Tag deleted successfully.'));
    }
}
