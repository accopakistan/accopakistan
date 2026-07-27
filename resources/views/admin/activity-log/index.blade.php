<x-admin-layout>
    <x-slot name="title">{{ __('Activity Log') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Activity Log') }}</h1>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Action') }}</th>
                        <th>{{ __('Subject') }}</th>
                        <th>{{ __('Changes') }}</th>
                        <th>{{ __('When') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr>
                            <td>{{ $activity->causer?->name ?? __('System') }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->subject_type ? class_basename($activity->subject_type) : '—' }} @if($activity->subject_id) #{{ $activity->subject_id }} @endif</td>
                            <td class="small text-muted" style="max-width: 300px;">
                                @if ($activity->properties->has('attributes'))
                                    {{ \Illuminate\Support\Str::limit(collect($activity->properties->get('attributes'))->keys()->join(', '), 80) }}
                                @endif
                            </td>
                            <td class="small">{{ $activity->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No activity recorded yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $activities->links() }}</div>
</x-admin-layout>
