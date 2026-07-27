<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Page::class);

        $pages = Page::with('author')->latest()->paginate(15);

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        $this->authorize('create', Page::class);

        return view('admin.pages.create', ['page' => new Page]);
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $page = DB::transaction(function () use ($data, $request) {
            $page = Page::create([
                ...collect($data)->except(['featured_image', 'seo_title', 'seo_description', 'seo_keywords'])->all(),
                'author_id' => $request->user()->id,
            ]);

            if ($page->is_homepage) {
                Page::where('id', '!=', $page->id)->update(['is_homepage' => false]);
            }

            if ($request->hasFile('featured_image')) {
                $page->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
            }

            $page->saveSeo([
                'title' => $data['seo_title'] ?? null,
                'description' => $data['seo_description'] ?? null,
                'keywords' => $data['seo_keywords'] ?? null,
            ]);

            return $page;
        });

        return redirect()->route('admin.pages.edit', $page)->with('status', __('Page created successfully.'));
    }

    public function edit(Page $page): View
    {
        $this->authorize('update', $page);

        $page->load('seo', 'blocks');

        return view('admin.pages.edit', compact('page'));
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $this->authorize('update', $page);

        $data = $request->validated();

        DB::transaction(function () use ($data, $request, $page) {
            $page->update(collect($data)->except(['featured_image', 'seo_title', 'seo_description', 'seo_keywords'])->all());

            if ($page->is_homepage) {
                Page::where('id', '!=', $page->id)->update(['is_homepage' => false]);
            }

            if ($request->hasFile('featured_image')) {
                $page->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
            }

            $page->saveSeo([
                'title' => $data['seo_title'] ?? null,
                'description' => $data['seo_description'] ?? null,
                'keywords' => $data['seo_keywords'] ?? null,
            ]);
        });

        return redirect()->route('admin.pages.edit', $page)->with('status', __('Page updated successfully.'));
    }

    public function destroy(Page $page): RedirectResponse
    {
        $this->authorize('delete', $page);

        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', __('Page deleted successfully.'));
    }
}
