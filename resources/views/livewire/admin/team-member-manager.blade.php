<div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary btn-sm" wire:click="openCreate">
            <i class="bi bi-plus-lg"></i> {{ __('Add Team Member') }}
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($members->isEmpty())
                <p class="text-muted small mb-0">{{ __('No team members yet.') }}</p>
            @else
                <div data-sortable data-sortable-call="reorder" class="d-flex flex-column gap-2">
                    @foreach ($members as $member)
                        <div wire:key="member-{{ $member->id }}" data-sortable-id="{{ $member->id }}" class="d-flex align-items-center justify-content-between border rounded p-2 {{ $member->is_active ? '' : 'opacity-50' }}">
                            <div class="d-flex align-items-center gap-2">
                                <span data-sortable-handle class="text-muted" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span>
                                @if ($member->photoUrl())
                                    <img src="{{ $member->photoUrl() }}" class="rounded-circle" style="width: 2.5rem; height: 2.5rem; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-body-tertiary d-flex align-items-center justify-content-center" style="width: 2.5rem; height: 2.5rem;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-medium small">{{ $member->name }}</div>
                                    <div class="text-muted small">{{ $member->position }}</div>
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $member->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $member->id }})" wire:confirm="{{ __('Remove this team member?') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="member-modal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <h3 class="modal-title h6">{{ $editingId ? __('Edit Team Member') : __('Add Team Member') }}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Name') }}</label>
                                <input type="text" class="form-control" wire:model="name">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Position') }}</label>
                                <input type="text" class="form-control" wire:model="position">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Department') }}</label>
                                <input type="text" class="form-control" wire:model="department">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Email') }}</label>
                                <input type="email" class="form-control" wire:model="email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Phone') }}</label>
                                <input type="text" class="form-control" wire:model="phone">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('LinkedIn URL') }}</label>
                                <input type="text" class="form-control" wire:model="linkedinUrl">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Twitter/X URL') }}</label>
                                <input type="text" class="form-control" wire:model="twitterUrl">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">{{ __('Photo') }}</label>
                                <input type="file" class="form-control" wire:model="photo" accept="image/*">
                            </div>
                            <div class="col-12">
                                <label class="form-label small">{{ __('Bio') }}</label>
                                <textarea class="form-control" rows="3" wire:model="bio"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="member-active" wire:model="isActive">
                                    <label class="form-check-label" for="member-active">{{ __('Active') }}</label>
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
    let memberModalEl = document.getElementById('member-modal');
    let memberModal = bootstrap.Modal.getOrCreateInstance(memberModalEl);

    $wire.on('open-member-modal', () => memberModal.show());
    $wire.on('close-member-modal', () => memberModal.hide());
</script>
@endscript
