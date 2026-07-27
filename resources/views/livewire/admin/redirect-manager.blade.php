<div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary btn-sm" wire:click="openCreate">
            <i class="bi bi-plus-lg"></i> {{ __('Add Redirect') }}
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('From') }}</th>
                        <th>{{ __('To') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Hits') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($redirects as $redirect)
                        <tr>
                            <td><code>{{ $redirect->from_path }}</code></td>
                            <td><code>{{ $redirect->to_path }}</code></td>
                            <td><span class="badge text-bg-secondary">{{ $redirect->type }}</span></td>
                            <td>{{ $redirect->hits }}</td>
                            <td>
                                @if ($redirect->is_active)
                                    <span class="badge text-bg-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge text-bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $redirect->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $redirect->id }})" wire:confirm="{{ __('Remove this redirect?') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">{{ __('No redirects yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="redirect-modal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <h3 class="modal-title h6">{{ $editingId ? __('Edit Redirect') : __('Add Redirect') }}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small">{{ __('From Path') }}</label>
                            <input type="text" class="form-control" wire:model="fromPath" placeholder="/old-page">
                            @error('fromPath') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('To Path or URL') }}</label>
                            <input type="text" class="form-control" wire:model="toPath" placeholder="/new-page">
                            @error('toPath') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Redirect Type') }}</label>
                            <select class="form-select" wire:model="type">
                                <option value="301">{{ __('301 Permanent') }}</option>
                                <option value="302">{{ __('302 Temporary') }}</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="redirect-active" wire:model="isActive">
                            <label class="form-check-label" for="redirect-active">{{ __('Active') }}</label>
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
    let redirectModalEl = document.getElementById('redirect-modal');
    let redirectModal = bootstrap.Modal.getOrCreateInstance(redirectModalEl);

    $wire.on('open-redirect-modal', () => redirectModal.show());
    $wire.on('close-redirect-modal', () => redirectModal.hide());

    @if (request()->query('from'))
        redirectModal.show();
    @endif
</script>
@endscript
