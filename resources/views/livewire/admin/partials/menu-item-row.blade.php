<div class="border rounded {{ $item->is_active ? '' : 'opacity-50' }}">
    <div class="d-flex align-items-center justify-content-between p-2 bg-body-tertiary">
        <div class="d-flex align-items-center gap-2">
            <span data-sortable-handle class="text-muted" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span>
            @if ($item->icon)
                <i class="{{ $item->icon }}"></i>
            @endif
            <span class="fw-medium small">{{ $item->title }}</span>
            <span class="text-muted small">{{ $item->url ?? $item->route_name ?? $item->page?->title }}</span>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="toggleActive({{ $item->id }})">
                <i class="bi {{ $item->is_active ? 'bi-eye' : 'bi-eye-slash' }}"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $item->id }})">
                <i class="bi bi-pencil"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeItem({{ $item->id }})" wire:confirm="{{ __('Remove this item?') }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>

    @if ($editingId === $item->id)
        <div class="p-3 border-top">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small">{{ __('Title') }}</label>
                    <input type="text" class="form-control form-control-sm" wire:model="editForm.title">
                </div>
                <div class="col-md-6">
                    <label class="form-label small">{{ __('Link Type') }}</label>
                    <select class="form-select form-select-sm" wire:model="editForm.type">
                        <option value="custom">{{ __('Custom URL') }}</option>
                        <option value="page">{{ __('Page') }}</option>
                        <option value="route">{{ __('Named Route') }}</option>
                    </select>
                </div>

                @if (($editForm['type'] ?? 'custom') === 'page')
                    <div class="col-md-6">
                        <label class="form-label small">{{ __('Page') }}</label>
                        <select class="form-select form-select-sm" wire:model="editForm.page_id">
                            <option value="">{{ __('Select a page') }}</option>
                            @foreach ($pages as $page)
                                <option value="{{ $page->id }}">{{ $page->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="col-md-6">
                        <label class="form-label small">{{ __('URL / Route') }}</label>
                        <input type="text" class="form-control form-control-sm" wire:model="editForm.url">
                    </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label small">{{ __('Icon Class') }}</label>
                    <input type="text" class="form-control form-control-sm" wire:model="editForm.icon">
                </div>
                <div class="col-md-6">
                    <label class="form-label small">{{ __('Target') }}</label>
                    <select class="form-select form-select-sm" wire:model="editForm.target">
                        <option value="_self">{{ __('Same tab') }}</option>
                        <option value="_blank">{{ __('New tab') }}</option>
                    </select>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="button" class="btn btn-sm btn-primary" wire:click="saveEdit">{{ __('Save') }}</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="$set('editingId', null)">{{ __('Cancel') }}</button>
            </div>
        </div>
    @endif
</div>
