<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
        <span class="fw-semibold">{{ __('Page Content Blocks') }}</span>

        <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-plus-lg"></i> {{ __('Add Block') }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach ($blockTypes as $type => $label)
                    <li><button type="button" class="dropdown-item" wire:click="addBlock('{{ $type }}')">{{ $label }}</button></li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="card-body">
        @if ($blocks->isEmpty())
            <p class="text-muted small mb-0">{{ __('No content blocks yet. Add one above to start building this page.') }}</p>
        @else
            <div data-sortable data-sortable-call="reorder" class="d-flex flex-column gap-2">
                @foreach ($blocks as $block)
                    <div wire:key="block-{{ $block->id }}" data-sortable-id="{{ $block->id }}" class="border rounded {{ $block->is_active ? '' : 'opacity-50' }}">
                        <div class="d-flex align-items-center justify-content-between p-2 bg-body-tertiary">
                            <div class="d-flex align-items-center gap-2">
                                <span data-sortable-handle class="text-muted" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span>
                                <span class="fw-medium small">{{ $blockTypes[$block->type] ?? $block->type }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="toggleActive({{ $block->id }})" title="{{ __('Show/Hide') }}">
                                    <i class="bi {{ $block->is_active ? 'bi-eye' : 'bi-eye-slash' }}"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="toggle({{ $block->id }})">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeBlock({{ $block->id }})" wire:confirm="{{ __('Remove this block?') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        @if ($openBlocks[$block->id] ?? false)
                            <div class="p-3">
                                @if ($block->type === 'heading_text')
                                    <div class="mb-2">
                                        <label class="form-label small">{{ __('Heading') }}</label>
                                        <input type="text" class="form-control form-control-sm" value="{{ $block->data['heading'] ?? '' }}"
                                            wire:change="updateBlockField({{ $block->id }}, 'heading', $event.target.value)">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small">{{ __('Body') }}</label>
                                        <textarea class="form-control form-control-sm" rows="4"
                                            wire:change="updateBlockField({{ $block->id }}, 'body', $event.target.value)">{{ $block->data['body'] ?? '' }}</textarea>
                                    </div>
                                @elseif ($block->type === 'image')
                                    <div class="mb-2">
                                        <label class="form-label small">{{ __('Image URL') }} <span class="text-muted">({{ __('copy from Media Library') }})</span></label>
                                        <input type="text" class="form-control form-control-sm" value="{{ $block->data['url'] ?? '' }}"
                                            wire:change="updateBlockField({{ $block->id }}, 'url', $event.target.value)">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">{{ __('Alt Text') }}</label>
                                        <input type="text" class="form-control form-control-sm" value="{{ $block->data['alt'] ?? '' }}"
                                            wire:change="updateBlockField({{ $block->id }}, 'alt', $event.target.value)">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small">{{ __('Caption') }}</label>
                                        <input type="text" class="form-control form-control-sm" value="{{ $block->data['caption'] ?? '' }}"
                                            wire:change="updateBlockField({{ $block->id }}, 'caption', $event.target.value)">
                                    </div>
                                @elseif ($block->type === 'cta')
                                    <div class="mb-2">
                                        <label class="form-label small">{{ __('Heading') }}</label>
                                        <input type="text" class="form-control form-control-sm" value="{{ $block->data['heading'] ?? '' }}"
                                            wire:change="updateBlockField({{ $block->id }}, 'heading', $event.target.value)">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">{{ __('Button Text') }}</label>
                                        <input type="text" class="form-control form-control-sm" value="{{ $block->data['button_text'] ?? '' }}"
                                            wire:change="updateBlockField({{ $block->id }}, 'button_text', $event.target.value)">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small">{{ __('Button URL') }}</label>
                                        <input type="text" class="form-control form-control-sm" value="{{ $block->data['button_url'] ?? '' }}"
                                            wire:change="updateBlockField({{ $block->id }}, 'button_url', $event.target.value)">
                                    </div>
                                @elseif ($block->type === 'gallery')
                                    <label class="form-label small">{{ __('Image URLs (one per line, copy from Media Library)') }}</label>
                                    <textarea class="form-control form-control-sm" rows="4"
                                        wire:change="updateBlockField({{ $block->id }}, 'images', $event.target.value.split('\n').filter(Boolean))">{{ implode("\n", $block->data['images'] ?? []) }}</textarea>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
