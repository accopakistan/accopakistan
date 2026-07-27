<div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary btn-sm" wire:click="openCreate">
            <i class="bi bi-plus-lg"></i> {{ __('Add Testimonial') }}
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($testimonials->isEmpty())
                <p class="text-muted small mb-0">{{ __('No testimonials yet.') }}</p>
            @else
                <div data-sortable data-sortable-call="reorder" class="d-flex flex-column gap-2">
                    @foreach ($testimonials as $testimonial)
                        <div wire:key="testimonial-{{ $testimonial->id }}" data-sortable-id="{{ $testimonial->id }}" class="d-flex align-items-start justify-content-between border rounded p-2 {{ $testimonial->is_active ? '' : 'opacity-50' }}">
                            <div class="d-flex align-items-start gap-2">
                                <span data-sortable-handle class="text-muted mt-1" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span>
                                @if ($testimonial->photoUrl())
                                    <img src="{{ $testimonial->photoUrl() }}" class="rounded-circle" style="width: 2.5rem; height: 2.5rem; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-body-tertiary d-flex align-items-center justify-content-center" style="width: 2.5rem; height: 2.5rem;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-medium small">{{ $testimonial->client_name }} @if($testimonial->company) &middot; {{ $testimonial->company }} @endif</div>
                                    <div class="text-muted small fst-italic">&ldquo;{{ \Illuminate\Support\Str::limit($testimonial->quote, 100) }}&rdquo;</div>
                                    @if ($testimonial->rating)
                                        <div class="text-warning small">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $testimonial->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $testimonial->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $testimonial->id }})" wire:confirm="{{ __('Remove this testimonial?') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="testimonial-modal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <h3 class="modal-title h6">{{ $editingId ? __('Edit Testimonial') : __('Add Testimonial') }}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Client Name') }}</label>
                                <input type="text" class="form-control" wire:model="clientName">
                                @error('clientName') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Client Position') }}</label>
                                <input type="text" class="form-control" wire:model="clientPosition">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Company') }}</label>
                                <input type="text" class="form-control" wire:model="company">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">{{ __('Rating') }}</label>
                                <select class="form-select" wire:model="rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">{{ __('Related Project') }}</label>
                                <select class="form-select" wire:model="projectId">
                                    <option value="">{{ __('None') }}</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Photo') }}</label>
                                <input type="file" class="form-control" wire:model="photo" accept="image/*">
                            </div>
                            <div class="col-12">
                                <label class="form-label small">{{ __('Quote') }}</label>
                                <textarea class="form-control" rows="3" wire:model="quote"></textarea>
                                @error('quote') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="testimonial-active" wire:model="isActive">
                                    <label class="form-check-label" for="testimonial-active">{{ __('Active') }}</label>
                                </div>
                            </div>
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
    let testimonialModalEl = document.getElementById('testimonial-modal');
    let testimonialModal = bootstrap.Modal.getOrCreateInstance(testimonialModalEl);

    $wire.on('open-testimonial-modal', () => testimonialModal.show());
    $wire.on('close-testimonial-modal', () => testimonialModal.hide());
</script>
@endscript
