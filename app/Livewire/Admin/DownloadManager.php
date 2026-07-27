<?php

namespace App\Livewire\Admin;

use App\Models\Download;
use Livewire\Component;
use Livewire\WithFileUploads;

class DownloadManager extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public string $title = '';

    public string $description = '';

    public string $category = '';

    public bool $isActive = true;

    public $file;

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:100'],
            'file' => [$this->editingId ? 'nullable' : 'required', 'file', 'max:20480'],
        ];
    }

    protected function authorizeManage(): void
    {
        abort_unless(auth()->user()->can('downloads.manage'), 403);
    }

    public function openCreate(): void
    {
        $this->authorizeManage();

        $this->reset(['editingId', 'title', 'description', 'category', 'file']);
        $this->isActive = true;
        $this->dispatch('open-download-modal');
    }

    public function edit(int $id): void
    {
        $download = Download::findOrFail($id);

        $this->editingId = $download->id;
        $this->title = $download->title;
        $this->description = $download->description ?? '';
        $this->category = $download->category ?? '';
        $this->isActive = $download->is_active;
        $this->file = null;

        $this->dispatch('open-download-modal');
    }

    public function save(): void
    {
        $this->authorizeManage();
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description ?: null,
            'category' => $this->category ?: null,
            'is_active' => $this->isActive,
        ];

        if ($this->file) {
            $data['file_size'] = $this->formatBytes($this->file->getSize());
        }

        if ($this->editingId) {
            $download = Download::findOrFail($this->editingId);
            $download->update($data);
        } else {
            $data['order'] = Download::max('order') + 1;
            $download = Download::create($data);
        }

        if ($this->file) {
            $download->addMedia($this->file->getRealPath())
                ->usingFileName($this->file->getClientOriginalName())
                ->toMediaCollection('file');
        }

        $this->dispatch('close-download-modal');
        session()->flash('status', __('Download saved successfully.'));
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 1).' '.$units[$i];
    }

    public function delete(int $id): void
    {
        $this->authorizeManage();

        Download::findOrFail($id)->delete();
        session()->flash('status', __('Download removed.'));
    }

    public function reorder(array $orderedIds): void
    {
        $this->authorizeManage();

        foreach ($orderedIds as $index => $id) {
            Download::where('id', $id)->update(['order' => $index]);
        }
    }

    public function render()
    {
        return view('livewire.admin.download-manager', [
            'downloads' => Download::orderBy('order')->get(),
        ]);
    }
}
