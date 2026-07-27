<x-admin-layout>
    <x-slot name="title">{{ __('Services') }}</x-slot>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Services') }}</h1>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> {{ __('New Service') }}
            </a>
        </div>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Featured') }}</th>
                        <th>{{ __('Order') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td>{{ $service->title }}</td>
                            <td>
                                @if ($service->status === 'published')
                                    <span class="badge text-bg-success">{{ __('Published') }}</span>
                                @else
                                    <span class="badge text-bg-secondary">{{ __('Draft') }}</span>
                                @endif
                            </td>
                            <td>{!! $service->is_featured ? '<i class="bi bi-star-fill text-warning"></i>' : '' !!}</td>
                            <td>{{ $service->order }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this service?') }}')">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No services yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $services->links() }}</div>
</x-admin-layout>
