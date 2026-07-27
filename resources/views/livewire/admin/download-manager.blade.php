<div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary btn-sm" wire:click="openCreate">
            <i class="bi bi-plus-lg"></i> {{ __('Add Download') }}
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($downloads->isEmpty())
                <p class="text-muted small mb-0">{{ __('No downloads yet.') }}</p>
            @else
                <div data-sortable data-sortable-call="reorder" class="d-flex flex-column gap-2">
                    @foreach ($downloads as $download)
                        <div wire:key="download-{{ $download->id }}" data-sortable-id="{{ $download->id }}" class="d-flex align-items-center justify-content-between border rounded p-2 {{ $download->is_active ? '' : 'opacity-50' }}">
                            <div class="d-flex align-items-center gap-2">
                                <span data-sortable-handle class="text-muted" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span>
                                <i class="bi bi-file-earmark-arrow-down fs-5 text-primary"></i>
                                <div>
                                    <div class="fw-medium small">{{ $download->title }}</div>
                                    <div class="text-muted small">
                                        @if ($download->category) <span class="badge text-bg-secondary">{{ $download->category }}</span> @endif
                                        {{ $download->file_size }} &middot; {{ $download->download_count }} {{ __('downloads') }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                @if ($download->fileUrl())
                                    <a href="{{ $download->fileUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></a>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $download->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $download->id }})" wire:confirm="{{ __('Remove this download?') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="download-modal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <h3 class="modal-title h6">{{ $editingId ? __('Edit Download') : __('Add Download') }}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Title') }}</label>
                            <input type="text" class="form-control" wire:model="title">
                            @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Description') }}</label>
                            <textarea class="form-control" rows="2" wire:model="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Category') }}</label>
                            <input type="text" class="form-control" wire:model="category" placeholder="e.g. Brochure, Certificate">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('File') }}</label>
                            <input type="file" class="form-control" wire:model="file">
                            @error('file') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="download-active" wire:model="isActive">
                            <label class="form-check-label" for="download-active">{{ __('Active') }}</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@script
<script>
    let downloadModalEl = document.getElementById('download-modal');
    let downloadModal = bootstrap.Modal.getOrCreateInstance(downloadModalEl);

    $wire.on('open-download-modal', () => downloadModal.show());
    $wire.on('close-download-modal', () => downloadModal.hide());
</script>
@endscript
