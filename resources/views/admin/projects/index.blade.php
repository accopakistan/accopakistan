<x-admin-layout>
    <x-slot name="title">{{ __('Projects') }}</x-slot>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Projects') }}</h1>
            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> {{ __('New Project') }}
            </a>
        </div>
    </x-slot>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">{{ __('Categories') }}</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-3">
                        @foreach ($categories as $category)
                            <li class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small">{{ $category->name }}</span>
                                <form action="{{ route('admin.project-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('{{ __('Delete this category?') }}')">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-x-lg"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                    <form action="{{ route('admin.project-categories.store') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="{{ __('New category') }}" required>
                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Client') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $project)
                                <tr>
                                    <td>{{ $project->title }}</td>
                                    <td>{{ $project->category?->name ?? '—' }}</td>
                                    <td>{{ $project->client ?? '—' }}</td>
                                    <td>
                                        @if ($project->status === 'published')
                                            <span class="badge text-bg-success">{{ __('Published') }}</span>
                                        @else
                                            <span class="badge text-bg-secondary">{{ __('Draft') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this project?') }}')">
                                            @csrf @method('delete')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No projects yet.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">{{ $projects->links() }}</div>
        </div>
    </div>
</x-admin-layout>
