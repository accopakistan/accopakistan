<x-admin-layout>
    <x-slot name="title">{{ __('Users') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Users') }}</h1>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Roles') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Joined') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span class="badge text-bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge text-bg-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge text-bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M j, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">{{ __('No users found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
</x-admin-layout>
