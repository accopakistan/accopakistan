<x-admin-layout>
    <x-slot name="title">{{ __('Job Applications') }}</x-slot>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Job Applications') }}</h1>
            <a href="{{ route('admin.careers.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Back to Job Postings') }}
            </a>
        </div>
    </x-slot>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">{{ __('Job Posting') }}</label>
                    <select name="job_posting_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">{{ __('All') }}</option>
                        @foreach ($jobPostings as $jobPosting)
                            <option value="{{ $jobPosting->id }}" @selected(request('job_posting_id') == $jobPosting->id)>{{ $jobPosting->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">{{ __('Status') }}</label>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">{{ __('All') }}</option>
                        @foreach (['new', 'reviewed', 'shortlisted', 'rejected', 'hired'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Applicant') }}</th>
                        <th>{{ __('Job Posting') }}</th>
                        <th>{{ __('Contact') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Applied') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $application)
                        <tr>
                            <td>{{ $application->name }}</td>
                            <td>{{ $application->jobPosting?->title ?? '—' }}</td>
                            <td>
                                <div class="small">{{ $application->email }}</div>
                                <div class="small text-muted">{{ $application->phone }}</div>
                            </td>
                            <td>
                                <form action="{{ route('admin.careers.applications.status', $application) }}" method="POST" class="d-inline">
                                    @csrf @method('put')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach (['new', 'reviewed', 'shortlisted', 'rejected', 'hired'] as $status)
                                            <option value="{{ $status }}" @selected($application->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>{{ $application->created_at->format('M j, Y') }}</td>
                            <td class="text-end">
                                @if ($application->resumeUrl())
                                    <a href="{{ $application->resumeUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-earmark-text"></i></a>
                                @endif
                                <form action="{{ route('admin.careers.applications.destroy', $application) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this application?') }}')">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">{{ __('No applications found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $applications->links() }}</div>
</x-admin-layout>
