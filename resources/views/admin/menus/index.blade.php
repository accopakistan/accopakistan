<x-admin-layout>
    <x-slot name="title">{{ __('Menus') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Menus') }}</h1>
    </x-slot>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">{{ __('Create Menu') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.menus.store') }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small">{{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('Location') }}</label>
                            <select name="location" class="form-select form-select-sm">
                                <option value="header">{{ __('Header') }}</option>
                                <option value="footer">{{ __('Footer') }}</option>
                                <option value="mobile">{{ __('Mobile') }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary w-100">{{ __('Create') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Location') }}</th>
                                <th>{{ __('Items') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menus as $menu)
                                <tr>
                                    <td>{{ $menu->name }}</td>
                                    <td><span class="badge text-bg-secondary">{{ $menu->location ?? '—' }}</span></td>
                                    <td>{{ $menu->items_count }}</td>
                                    <td>
                                        @if ($menu->is_active)
                                            <span class="badge text-bg-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge text-bg-secondary">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.menus.show', $menu) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i> {{ __('Manage') }}
                                        </a>
                                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this menu?') }}')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">{{ __('No menus yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
