<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold">{{ __('Add Menu Item') }}</div>
            <div class="card-body">
                <form wire:submit="addItem">
                    <div class="mb-2">
                        <label class="form-label small">{{ __('Title') }}</label>
                        <input type="text" class="form-control form-control-sm" wire:model="newTitle">
                        @error('newTitle') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-label small">{{ __('Link Type') }}</label>
                        <select class="form-select form-select-sm" wire:model.live="newType">
                            <option value="custom">{{ __('Custom URL') }}</option>
                            <option value="page">{{ __('Page') }}</option>
                            <option value="route">{{ __('Named Route') }}</option>
                        </select>
                    </div>

                    @if ($newType === 'page')
                        <div class="mb-2">
                            <label class="form-label small">{{ __('Page') }}</label>
                            <select class="form-select form-select-sm" wire:model="newPageId">
                                <option value="">{{ __('Select a page') }}</option>
                                @foreach ($pages as $page)
                                    <option value="{{ $page->id }}">{{ $page->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="mb-2">
                            <label class="form-label small">{{ $newType === 'route' ? __('Route Name') : __('URL') }}</label>
                            <input type="text" class="form-control form-control-sm" wire:model="newUrl" placeholder="{{ $newType === 'route' ? 'home' : 'https://...' }}">
                        </div>
                    @endif

                    <div class="mb-2">
                        <label class="form-label small">{{ __('Parent Item') }}</label>
                        <select class="form-select form-select-sm" wire:model="newParentId">
                            <option value="">{{ __('None (top level)') }}</option>
                            @foreach ($allItems as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small">{{ __('Icon Class') }}</label>
                            <input type="text" class="form-control form-control-sm" wire:model="newIcon" placeholder="bi bi-house">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">{{ __('Target') }}</label>
                            <select class="form-select form-select-sm" wire:model="newTarget">
                                <option value="_self">{{ __('Same tab') }}</option>
                                <option value="_blank">{{ __('New tab') }}</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-plus-lg"></i> {{ __('Add Item') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold">{{ __('Menu Structure') }}</div>
            <div class="card-body">
                @if ($topLevelItems->isEmpty())
                    <p class="text-muted small mb-0">{{ __('No items yet. Add one on the left to get started.') }}</p>
                @else
                    <div data-sortable data-sortable-call="reorder" class="d-flex flex-column gap-2">
                        @foreach ($topLevelItems as $item)
                            <div wire:key="item-{{ $item->id }}" data-sortable-id="{{ $item->id }}">
                                @include('livewire.admin.partials.menu-item-row', ['item' => $item])

                                @if ($item->children->isNotEmpty())
                                    <div data-sortable data-sortable-call="reorder" data-sortable-parent="{{ $item->id }}" class="d-flex flex-column gap-2 ms-4 mt-2">
                                        @foreach ($item->children as $child)
                                            <div wire:key="item-{{ $child->id }}" data-sortable-id="{{ $child->id }}">
                                                @include('livewire.admin.partials.menu-item-row', ['item' => $child])
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
