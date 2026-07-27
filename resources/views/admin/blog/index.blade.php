<x-admin-layout>
    <x-slot name="title">{{ __('Blog') }}</x-slot>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Blog') }}</h1>
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> {{ __('New Post') }}
            </a>
        </div>
    </x-slot>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent fw-semibold">{{ __('Categories') }}</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-3">
                        @foreach ($categories as $category)
                            <li class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small">{{ $category->name }}</span>
                                <form action="{{ route('admin.blog-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('{{ __('Delete this category?') }}')">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-x-lg"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                    <form action="{{ route('admin.blog-categories.store') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="{{ __('New category') }}" required>
                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i></button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">{{ __('Tags') }}</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        @foreach ($tags as $tag)
                            <form action="{{ route('admin.blog-tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('{{ __('Delete this tag?') }}')">
                                @csrf @method('delete')
                                <button type="submit" class="btn btn-sm btn-outline-secondary">{{ $tag->name }} &times;</button>
                            </form>
                        @endforeach
                    </div>
                    <form action="{{ route('admin.blog-tags.store') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="{{ __('New tag') }}" required>
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
                                <th>{{ __('Author') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->category?->name ?? '—' }}</td>
                                    <td>{{ $post->author?->name ?? '—' }}</td>
                                    <td>
                                        @if ($post->status === 'published')
                                            <span class="badge text-bg-success">{{ __('Published') }}</span>
                                        @else
                                            <span class="badge text-bg-secondary">{{ __('Draft') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this post?') }}')">
                                            @csrf @method('delete')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No posts yet.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">{{ $posts->links() }}</div>
        </div>
    </div>
</x-admin-layout>
