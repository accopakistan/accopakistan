<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::withCount('items')->orderBy('name')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function show(Menu $menu): View
    {
        return view('admin.menus.show', compact('menu'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:100'],
        ]);

        $menu = Menu::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::random(4),
            'location' => $data['location'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.menus.show', $menu)->with('status', __('Menu created successfully.'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('status', __('Menu updated successfully.'));
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('status', __('Menu deleted successfully.'));
    }
}
