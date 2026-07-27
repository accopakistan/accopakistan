<x-admin-layout>
    <x-slot name="title">{{ __('Leads') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Leads') }}</h1>
    </x-slot>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">{{ __('Status') }}</label>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">{{ __('All') }}</option>
                        @foreach (['new', 'contacted', 'closed'] as $status)
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
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Contact') }}</th>
                        <th>{{ __('Subject') }}</th>
                        <th>{{ __('Message') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Received') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>{{ $lead->name }}</td>
                            <td>
                                <div class="small">{{ $lead->email }}</div>
                                <div class="small text-muted">{{ $lead->phone }}</div>
                            </td>
                            <td>{{ $lead->subject ?? '—' }}</td>
                            <td class="small" style="max-width: 240px;">{{ \Illuminate\Support\Str::limit($lead->message, 80) }}</td>
                            <td>
                                <form action="{{ route('admin.leads.status', $lead) }}" method="POST">
                                    @csrf @method('put')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach (['new', 'contacted', 'closed'] as $status)
                                            <option value="{{ $status }}" @selected($lead->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>{{ $lead->created_at->format('M j, Y') }}</td>
                            <td class="text-end">
                                <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('{{ __('Delete this lead?') }}')">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">{{ __('No leads yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $leads->links() }}</div>
</x-admin-layout>
