<x-admin-layout>
    <x-slot name="title">{{ __('404 Monitor') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('404 Monitor') }}</h1>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Path') }}</th>
                        <th>{{ __('Referrer') }}</th>
                        <th>{{ __('Hits') }}</th>
                        <th>{{ __('Last Seen') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td><code>{{ $log->path }}</code></td>
                            <td class="small text-muted">{{ $log->referrer ?? '—' }}</td>
                            <td>{{ $log->hits }}</td>
                            <td>{{ $log->last_seen_at?->diffForHumans() }}</td>
                            <td class="text-end">
                                @can('redirects.manage')
                                    <a href="{{ route('admin.redirects.index', ['from' => $log->path]) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-signpost-split"></i> {{ __('Create Redirect') }}
                                    </a>
                                @endcan
                                <form action="{{ route('admin.not-found-logs.destroy', $log) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Remove this entry?') }}')">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No 404s recorded yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $logs->links() }}</div>
</x-admin-layout>
