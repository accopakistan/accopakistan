<x-admin-layout>
    <x-slot name="title">{{ __('Backups') }}</x-slot>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Backups') }}</h1>
            <form action="{{ route('admin.backups.run') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-cloud-arrow-up"></i> {{ __('Run Backup Now') }}
                </button>
            </form>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('File') }}</th>
                        <th>{{ __('Disk') }}</th>
                        <th>{{ __('Size') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th class="text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($backups as $backup)
                        <tr>
                            <td><code>{{ basename($backup['path']) }}</code></td>
                            <td>{{ $backup['disk'] }}</td>
                            <td>{{ number_format($backup['size'] / 1048576, 2) }} MB</td>
                            <td>{{ $backup['date']->format('M j, Y H:i') }}</td>
                            <td class="text-end">
                                <form action="{{ route('admin.backups.destroy') }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this backup?') }}')">
                                    @csrf @method('delete')
                                    <input type="hidden" name="disk" value="{{ $backup['disk'] }}">
                                    <input type="hidden" name="path" value="{{ $backup['path'] }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No backups yet. Run one above.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
