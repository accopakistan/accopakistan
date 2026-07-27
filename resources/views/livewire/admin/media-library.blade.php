<div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h2 class="h6 fw-semibold mb-3">{{ __('Folders') }}</h2>

                    <div class="nav flex-column gap-1 mb-3">
                        <button type="button" wire:click="selectFolder(null)" class="btn btn-sm text-start {{ is_null($folderId) ? 'btn-primary' : 'btn-outline-secondary border-0' }}">
                            <i class="bi bi-collection"></i> {{ __('All Media') }}
                        </button>
                        @foreach ($folders as $folder)
                            <button type="button" wire:click="selectFolder({{ $folder->id }})" class="btn btn-sm text-start {{ $folderId === $folder->id ? 'btn-primary' : 'btn-outline-secondary border-0' }}">
                                <i class="bi bi-folder"></i> {{ $folder->name }}
                            </button>
                        @endforeach
                    </div>

                    <form wire:submit="createFolder" class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" placeholder="{{ __('New folder') }}" wire:model="newFolderName">
                        <button class="btn btn-sm btn-outline-primary" type="submit">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </form>
                    @error('newFolderName') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h6 fw-semibold mb-3">{{ __('Upload') }}</h2>
                    <form wire:submit="upload">
                        <input type="file" class="form-control form-control-sm mb-2" wire:model="uploads" multiple accept="image/*">
                        @error('uploads.*') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                        <div wire:loading wire:target="uploads" class="small text-muted mb-2">{{ __('Uploading...') }}</div>

                        @if (!empty($uploads))
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @foreach ($uploads as $file)
                                    <img src="{{ $file->temporaryUrl() }}" class="rounded border" style="width: 3rem; height: 3rem; object-fit: cover;">
                                @endforeach
                            </div>
                        @endif

                        <button class="btn btn-sm btn-primary w-100" type="submit" @if(empty($uploads)) disabled @endif>
                            {{ __('Upload') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <input type="text" class="form-control mb-3" placeholder="{{ __('Search files...') }}" wire:model.live.debounce.400ms="search">

                    <div class="row g-3">
                        @forelse ($media as $item)
                            <div class="col-6 col-md-4 col-xl-3">
                                <div class="border rounded overflow-hidden h-100">
                                    <img src="{{ $item->getUrl() }}" class="w-100" style="height: 8rem; object-fit: cover;" alt="{{ $item->getCustomProperty('alt', '') }}">
                                    <div class="p-2">
                                        <div class="small text-truncate" title="{{ $item->file_name }}">{{ $item->file_name }}</div>
                                        <div class="d-flex gap-1 mt-1">
                                            <button type="button" class="btn btn-sm btn-outline-secondary flex-fill" wire:click="edit({{ $item->id }})" data-bs-toggle="modal" data-bs-target="#media-edit-modal">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger flex-fill" wire:click="delete({{ $item->id }})" wire:confirm="{{ __('Delete this file?') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">{{ __('No media found.') }}</div>
                        @endforelse
                    </div>

                    <div class="mt-3">
                        {{ $media->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="media-edit-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit="saveEdit">
                    <div class="modal-header">
                        <h3 class="modal-title h6">{{ __('Edit Media Details') }}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Alt Text') }}</label>
                            <input type="text" class="form-control" wire:model="editingAlt">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('Title') }}</label>
                            <input type="text" class="form-control" wire:model="editingTitle">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
