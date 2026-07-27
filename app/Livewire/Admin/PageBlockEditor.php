<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use App\Models\PageBlock;
use Livewire\Component;

class PageBlockEditor extends Component
{
    public Page $page;

    public array $openBlocks = [];

    public array $blockTypes = [
        'heading_text' => 'Heading & Text',
        'image' => 'Image',
        'cta' => 'Call to Action',
        'gallery' => 'Gallery',
    ];

    public function mount(Page $page): void
    {
        $this->page = $page;
    }

    public function addBlock(string $type): void
    {
        abort_unless(array_key_exists($type, $this->blockTypes), 422);

        $defaults = match ($type) {
            'heading_text' => ['heading' => '', 'body' => ''],
            'image' => ['url' => '', 'alt' => '', 'caption' => ''],
            'cta' => ['heading' => '', 'button_text' => '', 'button_url' => ''],
            'gallery' => ['images' => []],
        };

        $block = $this->page->blocks()->create([
            'type' => $type,
            'data' => $defaults,
            'order' => $this->page->blocks()->max('order') + 1,
            'is_active' => true,
        ]);

        $this->openBlocks[$block->id] = true;
    }

    public function toggle(int $blockId): void
    {
        $this->openBlocks[$blockId] = ! ($this->openBlocks[$blockId] ?? false);
    }

    public function updateBlockField(int $blockId, string $field, $value): void
    {
        $block = PageBlock::where('page_id', $this->page->id)->findOrFail($blockId);
        $data = $block->data ?? [];
        $data[$field] = $value;
        $block->update(['data' => $data]);
    }

    public function toggleActive(int $blockId): void
    {
        $block = PageBlock::where('page_id', $this->page->id)->findOrFail($blockId);
        $block->update(['is_active' => ! $block->is_active]);
    }

    public function removeBlock(int $blockId): void
    {
        PageBlock::where('page_id', $this->page->id)->findOrFail($blockId)->delete();
    }

    public function reorder(array $orderedIds, ?string $parentId = null): void
    {
        foreach ($orderedIds as $index => $id) {
            PageBlock::where('page_id', $this->page->id)->where('id', $id)->update(['order' => $index]);
        }
    }

    public function render()
    {
        return view('livewire.admin.page-block-editor', [
            'blocks' => $this->page->blocks()->get(),
        ]);
    }
}
