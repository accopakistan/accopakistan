<x-admin-layout>
    <x-slot name="title">{{ __('Dashboard') }}</x-slot>
    <x-slot name="header">
        <h1 class="h4 fw-semibold mb-0 font-heading">{{ __('Dashboard') }}</h1>
    </x-slot>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="admin-stat-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ __('Total Users') }}</div>
                        <div class="fs-4 fw-semibold">{{ $stats['users'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="admin-stat-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ __('Published Pages') }}</div>
                        <div class="fs-4 fw-semibold">{{ $stats['published_pages'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="admin-stat-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ __('Published Projects') }}</div>
                        <div class="fs-4 fw-semibold">{{ $stats['projects'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="admin-stat-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ __('Published Posts') }}</div>
                        <div class="fs-4 fw-semibold">{{ $stats['blog_posts'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="admin-stat-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ __('New Leads') }}</div>
                        <div class="fs-4 fw-semibold">{{ $stats['leads_new'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="admin-stat-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ __('New Applications') }}</div>
                        <div class="fs-4 fw-semibold">{{ $stats['applications_new'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="admin-stat-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ __('Published Services') }}</div>
                        <div class="fs-4 fw-semibold">{{ $stats['services'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="admin-stat-card card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger-subtle text-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="text-muted small">{{ __('404 Errors Logged') }}</div>
                        <div class="fs-4 fw-semibold">{{ $stats['not_found_count'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent fw-semibold">{{ __('Recent Activity') }}</div>
                <div class="card-body">
                    @if ($recentActivity->isEmpty())
                        <p class="text-muted small mb-0">{{ __('No activity recorded yet.') }}</p>
                    @else
                        <ul class="list-unstyled mb-0">
                            @foreach ($recentActivity as $activity)
                                <li class="d-flex justify-content-between border-bottom py-2 small">
                                    <span>
                                        <strong>{{ $activity->causer?->name ?? __('System') }}</strong>
                                        {{ $activity->description }}
                                        @if ($activity->subject_type)
                                            <span class="text-muted">({{ class_basename($activity->subject_type) }})</span>
                                        @endif
                                    </span>
                                    <span class="text-muted">{{ $activity->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center">
                    {{ __('Recent Leads') }}
                    @can('leads.view')
                        <a href="{{ route('admin.leads.index') }}" class="small">{{ __('View all') }}</a>
                    @endcan
                </div>
                <div class="card-body">
                    @if ($recentLeads->isEmpty())
                        <p class="text-muted small mb-0">{{ __('No leads yet.') }}</p>
                    @else
                        <ul class="list-unstyled mb-0">
                            @foreach ($recentLeads as $lead)
                                <li class="d-flex justify-content-between border-bottom py-2 small">
                                    <span>{{ $lead->name }} <span class="text-muted">&middot; {{ $lead->email }}</span></span>
                                    <span class="badge text-bg-secondary">{{ ucfirst($lead->status) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
