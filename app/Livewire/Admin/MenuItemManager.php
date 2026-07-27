<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Livewire\Component;

class MenuItemManager extends Component
{
    public Menu $menu;

    public string $newTitle = '';

    public string $newType = 'custom';

    public string $newUrl = '';

    public ?int $newPageId = null;

    public ?int $newParentId = null;

    public string $newIcon = '';

    public string $newTarget = '_self';

    public ?int $editingId = null;

    public array $editForm = [];

    public function mount(Menu $menu): void
    {
        $this->menu = $menu;
    }

    protected function rules(): array
    {
        return [
            'newTitle' => ['required', 'string', 'max:255'],
            'newType' => ['required', 'in:custom,page,route'],
            'newUrl' => ['nullable', 'string', 'max:255'],
            'newPageId' => ['nullable', 'exists:pages,id'],
            'newParentId' => ['nullable', 'exists:menu_items,id'],
        ];
    }

    protected function authorizeManage(): void
    {
        abort_unless(auth()->user()->can('menus.manage'), 403);
    }

    public function addItem(): void
    {
        $this->authorizeManage();
        $this->validate();

        $this->menu->items()->create([
            'parent_id' => $this->newParentId,
            'page_id' => $this->newType === 'page' ? $this->newPageId : null,
            'title' => $this->newTitle,
            'url' => $this->newType === 'custom' ? $this->newUrl : null,
            'route_name' => $this->newType === 'route' ? $this->newUrl : null,
            'icon' => $this->newIcon ?: null,
            'target' => $this->newTarget,
            'order' => $this->menu->items()->where('parent_id', $this->newParentId)->max('order') + 1,
            'is_active' => true,
        ]);

        $this->reset(['newTitle', 'newUrl', 'newPageId', 'newParentId', 'newIcon']);
        $this->newType = 'custom';
        $this->newTarget = '_self';
    }

    public function edit(int $itemId): void
    {
        $item = MenuItem::where('menu_id', $this->menu->id)->findOrFail($itemId);

        $this->editingId = $item->id;
        $this->editForm = [
            'title' => $item->title,
            'type' => $item->page_id ? 'page' : ($item->route_name ? 'route' : 'custom'),
            'url' => $item->url ?? $item->route_name ?? '',
            'page_id' => $item->page_id,
            'parent_id' => $item->parent_id,
            'icon' => $item->icon ?? '',
            'target' => $item->target,
        ];
    }

    public function saveEdit(): void
    {
        $this->authorizeManage();

        $item = MenuItem::where('menu_id', $this->menu->id)->findOrFail($this->editingId);

        $item->update([
            'title' => $this->editForm['title'],
            'url' => $this->editForm['type'] === 'custom' ? $this->editForm['url'] : null,
            'route_name' => $this->editForm['type'] === 'route' ? $this->editForm['url'] : null,
            'page_id' => $this->editForm['type'] === 'page' ? $this->editForm['page_id'] : null,
            'parent_id' => $this->editForm['parent_id'] !== $item->id ? $this->editForm['parent_id'] : null,
            'icon' => $this->editForm['icon'] ?: null,
            'target' => $this->editForm['target'],
        ]);

        $this->editingId = null;
    }

    public function toggleActive(int $itemId): void
    {
        $this->authorizeManage();

        $item = MenuItem::where('menu_id', $this->menu->id)->findOrFail($itemId);
        $item->update(['is_active' => ! $item->is_active]);
    }

    public function removeItem(int $itemId): void
    {
        $this->authorizeManage();

        MenuItem::where('menu_id', $this->menu->id)->findOrFail($itemId)->delete();
    }

    public function reorder(array $orderedIds, ?string $parentId = null): void
    {
        $this->authorizeManage();

        foreach ($orderedIds as $index => $id) {
            MenuItem::where('menu_id', $this->menu->id)
                ->where('id', $id)
                ->update(['order' => $index, 'parent_id' => $parentId]);
        }
    }

    public function render()
    {
        $items = $this->menu->items()->with('children')->whereNull('parent_id')->orderBy('order')->get();
        $allItems = $this->menu->items()->orderBy('order')->get();
        $pages = Page::orderBy('title')->get(['id', 'title']);

        return view('livewire.admin.menu-item-manager', [
            'topLevelItems' => $items,
            'allItems' => $allItems,
            'pages' => $pages,
        ]);
    }
}
