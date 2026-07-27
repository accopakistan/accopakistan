<div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary btn-sm" wire:click="openCreate">
            <i class="bi bi-plus-lg"></i> {{ __('Add FAQ') }}
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($faqs->isEmpty())
                <p class="text-muted small mb-0">{{ __('No FAQs yet.') }}</p>
            @else
                <div data-sortable data-sortable-call="reorder" class="d-flex flex-column gap-2">
                    @foreach ($faqs as $faq)
                        <div wire:key="faq-{{ $faq->id }}" data-sortable-id="{{ $faq->id }}" class="d-flex align-items-center justify-content-between border rounded p-2 {{ $faq->is_active ? '' : 'opacity-50' }}">
                            <div class="d-flex align-items-center gap-2">
                                <span data-sortable-handle class="text-muted" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span>
                                <div>
                                    <div class="fw-medium small">{{ $faq->question }}</div>
                                    @if ($faq->category)
                                        <span class="badge text-bg-secondary">{{ $faq->category }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $faq->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $faq->id }})" wire:confirm="{{ __('Remove this FAQ?') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="faq-modal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <h3 class="modal-title h6">{{ $editingId ? __('Edit FAQ') : __('Add FAQ') }}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Question') }}</label>
                            <input type="text" class="form-control" wire:model="question">
                            @error('question') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Answer') }}</label>
                            <textarea class="form-control" rows="4" wire:model="answer"></textarea>
                            @error('answer') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Category') }}</label>
                            <input type="text" class="form-control" wire:model="category" placeholder="e.g. General, Services, Careers">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="faq-active" wire:model="isActive">
                            <label class="form-check-label" for="faq-active">{{ __('Active') }}</label>
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
    let faqModalEl = document.getElementById('faq-modal');
    let faqModal = bootstrap.Modal.getOrCreateInstance(faqModalEl);

    $wire.on('open-faq-modal', () => faqModal.show());
    $wire.on('close-faq-modal', () => faqModal.hide());
</script>
@endscript
