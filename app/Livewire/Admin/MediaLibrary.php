<?php

namespace App\Livewire\Admin;

use App\Models\MediaFolder;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaLibrary extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public ?int $folderId = null;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $uploads = [];

    public string $newFolderName = '';

    public ?int $editingId = null;

    public string $editingAlt = '';

    public string $editingTitle = '';

    protected function rules(): array
    {
        return [
            'uploads.*' => 'image|max:5120',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function selectFolder(?int $folderId): void
    {
        $this->folderId = $folderId;
        $this->resetPage();
    }

    protected function authorizeManage(): void
    {
        abort_unless(auth()->user()->can('media.manage'), 403);
    }

    public function createFolder(): void
    {
        $this->authorizeManage();
        $this->validate(['newFolderName' => 'required|string|max:100']);

        $folder = MediaFolder::create([
            'name' => $this->newFolderName,
            'slug' => Str::slug($this->newFolderName).'-'.Str::random(4),
        ]);

        $this->newFolderName = '';
        $this->folderId = $folder->id;
    }

    public function upload(): void
    {
        $this->authorizeManage();
        $this->validate(['uploads.*' => 'image|max:5120']);

        $folder = $this->folderId
            ? MediaFolder::findOrFail($this->folderId)
            : MediaFolder::firstOrCreate(['slug' => 'general'], ['name' => 'General']);

        foreach ($this->uploads as $file) {
            $folder->addMedia($file->getRealPath())
                ->usingFileName($file->getClientOriginalName())
                ->toMediaCollection('files');
        }

        $this->uploads = [];
        $this->folderId = $folder->id;
        session()->flash('status', __('Media uploaded successfully.'));
    }

    public function edit(int $mediaId): void
    {
        $media = Media::findOrFail($mediaId);
        $this->editingId = $media->id;
        $this->editingAlt = $media->getCustomProperty('alt', '');
        $this->editingTitle = $media->getCustomProperty('title', '');
    }

    public function saveEdit(): void
    {
        $this->authorizeManage();

        $media = Media::findOrFail($this->editingId);
        $media->setCustomProperty('alt', $this->editingAlt);
        $media->setCustomProperty('title', $this->editingTitle);
        $media->save();

        $this->editingId = null;
        session()->flash('status', __('Media details updated.'));
    }

    public function delete(int $mediaId): void
    {
        $this->authorizeManage();

        Media::findOrFail($mediaId)->delete();
        session()->flash('status', __('Media deleted.'));
    }

    public function render()
    {
        $media = Media::query()
            ->when($this->folderId, fn ($q) => $q->where('model_type', MediaFolder::class)->where('model_id', $this->folderId))
            ->when($this->search, fn ($q) => $q->where('file_name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(24);

        $folders = MediaFolder::orderBy('name')->get();

        return view('livewire.admin.media-library', [
            'media' => $media,
            'folders' => $folders,
        ]);
    }
}
