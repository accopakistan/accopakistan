<div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary btn-sm" wire:click="openCreate">
            <i class="bi bi-plus-lg"></i> {{ __('Add Client') }}
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($clients->isEmpty())
                <p class="text-muted small mb-0">{{ __('No clients yet.') }}</p>
            @else
                <div data-sortable data-sortable-call="reorder" class="row g-3">
                    @foreach ($clients as $client)
                        <div wire:key="client-{{ $client->id }}" data-sortable-id="{{ $client->id }}" class="col-6 col-md-3">
                            <div class="border rounded p-3 text-center h-100 {{ $client->is_active ? '' : 'opacity-50' }}">
                                <div class="d-flex justify-content-between mb-2">
                                    <span data-sortable-handle class="text-muted" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $client->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $client->id }})" wire:confirm="{{ __('Remove this client?') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @if ($client->logoUrl())
                                    <img src="{{ $client->logoUrl() }}" class="img-fluid mb-2" style="max-height: 3rem;">
                                @else
                                    <div class="bg-body-tertiary rounded d-flex align-items-center justify-content-center mb-2" style="height: 3rem;">
                                        <i class="bi bi-building text-muted"></i>
                                    </div>
                                @endif
                                <div class="small fw-medium">{{ $client->name }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="client-modal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <h3 class="modal-title h6">{{ $editingId ? __('Edit Client') : __('Add Client') }}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Name') }}</label>
                            <input type="text" class="form-control" wire:model="name">
                            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Website URL') }}</label>
                            <input type="text" class="form-control" wire:model="websiteUrl">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Logo') }}</label>
                            <input type="file" class="form-control" wire:model="logo" accept="image/*">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="client-active" wire:model="isActive">
                            <label class="form-check-label" for="client-active">{{ __('Active') }}</label>
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
    let clientModalEl = document.getElementById('client-modal');
    let clientModal = bootstrap.Modal.getOrCreateInstance(clientModalEl);

    $wire.on('open-client-modal', () => clientModal.show());
    $wire.on('close-client-modal', () => clientModal.hide());
</script>
@endscript
