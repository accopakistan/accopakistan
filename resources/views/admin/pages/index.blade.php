<x-admin-layout>
    <x-slot name="title">{{ __('Pages') }}</x-slot>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Pages') }}</h1>
            @can('pages.create')
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> {{ __('New Page') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Author') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Updated') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pages as $page)
                        <tr>
                            <td>
                                {{ $page->title }}
                                @if ($page->is_homepage)
                                    <span class="badge text-bg-info ms-1">{{ __('Homepage') }}</span>
                                @endif
                            </td>
                            <td><code>/{{ $page->slug }}</code></td>
                            <td>{{ $page->author?->name ?? '—' }}</td>
                            <td>
                                @if ($page->status === 'published')
                                    <span class="badge text-bg-success">{{ __('Published') }}</span>
                                @else
                                    <span class="badge text-bg-secondary">{{ __('Draft') }}</span>
                                @endif
                            </td>
                            <td>{{ $page->updated_at->format('M j, Y') }}</td>
                            <td class="text-end">
                                @can('pages.edit')
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can('pages.delete')
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this page?') }}')">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">{{ __('No pages yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $pages->links() }}
    </div>
</x-admin-layout>
