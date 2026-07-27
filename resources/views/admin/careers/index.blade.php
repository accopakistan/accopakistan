<x-admin-layout>
    <x-slot name="title">{{ __('Careers') }}</x-slot>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Job Postings') }}</h1>
            <a href="{{ route('admin.careers.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> {{ __('New Job Posting') }}
            </a>
        </div>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Department') }}</th>
                        <th>{{ __('Location') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Applications') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jobPostings as $jobPosting)
                        <tr>
                            <td>{{ $jobPosting->title }}</td>
                            <td>{{ $jobPosting->department ?? '—' }}</td>
                            <td>{{ $jobPosting->location ?? '—' }}</td>
                            <td><span class="badge text-bg-secondary">{{ ucfirst($jobPosting->type) }}</span></td>
                            <td>
                                @if ($jobPosting->status === 'open')
                                    <span class="badge text-bg-success">{{ __('Open') }}</span>
                                @else
                                    <span class="badge text-bg-secondary">{{ __('Closed') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.careers.applications.index', ['job_posting_id' => $jobPosting->id]) }}">
                                    {{ $jobPosting->applications_count }}
                                </a>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.careers.edit', $jobPosting) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.careers.destroy', $jobPosting) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this job posting?') }}')">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">{{ __('No job postings yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $jobPostings->links() }}</div>
</x-admin-layout>
