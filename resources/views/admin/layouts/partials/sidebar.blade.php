<aside class="admin-sidebar p-3">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand d-flex align-items-center text-white text-decoration-none mb-4 px-1">
        <span class="fs-5">ACCO <span class="text-warning">Pakistan</span></span>
    </a>

    <nav class="nav flex-column gap-1">
        @can('dashboard.view')
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> {{ __('Dashboard') }}
            </a>
        @endcan

        @can('pages.view')
            <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> {{ __('Pages') }}
            </a>
        @endcan

        @can('services.view')
            <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <i class="bi bi-tools"></i> {{ __('Services') }}
            </a>
        @endcan

        @can('projects.view')
            <a href="{{ route('admin.projects.index') }}" class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                <i class="bi bi-buildings"></i> {{ __('Projects') }}
            </a>
        @endcan

        @can('blog.view')
            <a href="{{ route('admin.blog.index') }}" class="nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i> {{ __('Blog') }}
            </a>
        @endcan

        @can('team.view')
            <a href="{{ route('admin.team.index') }}" class="nav-link {{ request()->routeIs('admin.team.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> {{ __('Team') }}
            </a>
        @endcan

        @can('testimonials.view')
            <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <i class="bi bi-chat-quote"></i> {{ __('Testimonials') }}
            </a>
        @endcan

        @can('clients.view')
            <a href="{{ route('admin.clients.index') }}" class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> {{ __('Clients') }}
            </a>
        @endcan

        @can('careers.view')
            <a href="{{ route('admin.careers.index') }}" class="nav-link {{ request()->routeIs('admin.careers.*') && !request()->routeIs('admin.careers.applications.*') ? 'active' : '' }}">
                <i class="bi bi-briefcase"></i> {{ __('Careers') }}
            </a>
        @endcan

        @can('job_applications.view')
            <a href="{{ route('admin.careers.applications.index') }}" class="nav-link {{ request()->routeIs('admin.careers.applications.*') ? 'active' : '' }}">
                <i class="bi bi-inbox"></i> {{ __('Applications') }}
            </a>
        @endcan

        @can('leads.view')
            <a href="{{ route('admin.leads.index') }}" class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i> {{ __('Leads') }}
            </a>
        @endcan

        @can('faqs.view')
            <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> {{ __('FAQs') }}
            </a>
        @endcan

        @can('downloads.view')
            <a href="{{ route('admin.downloads.index') }}" class="nav-link {{ request()->routeIs('admin.downloads.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-arrow-down"></i> {{ __('Downloads') }}
            </a>
        @endcan

        @can('menus.view')
            <a href="{{ route('admin.menus.index') }}" class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <i class="bi bi-list-nested"></i> {{ __('Menus') }}
            </a>
        @endcan

        @can('media.view')
            <a href="{{ route('admin.media.index') }}" class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i> {{ __('Media Library') }}
            </a>
        @endcan

        @can('seo.view')
            <a href="{{ route('admin.seo.index') }}" class="nav-link {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> {{ __('SEO Tools') }}
            </a>
        @endcan

        @can('redirects.view')
            <a href="{{ route('admin.redirects.index') }}" class="nav-link {{ request()->routeIs('admin.redirects.*') ? 'active' : '' }}">
                <i class="bi bi-signpost-split"></i> {{ __('Redirects') }}
            </a>
        @endcan

        @can('logs.view')
            <a href="{{ route('admin.not-found-logs.index') }}" class="nav-link {{ request()->routeIs('admin.not-found-logs.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i> {{ __('404 Monitor') }}
            </a>
            <a href="{{ route('admin.activity-log.index') }}" class="nav-link {{ request()->routeIs('admin.activity-log.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> {{ __('Activity Log') }}
            </a>
        @endcan

        @can('backups.view')
            <a href="{{ route('admin.backups.index') }}" class="nav-link {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}">
                <i class="bi bi-cloud-arrow-down"></i> {{ __('Backups') }}
            </a>
        @endcan

        @can('users.view')
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> {{ __('Users') }}
            </a>
        @endcan

        @can('settings.view')
            <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> {{ __('Settings') }}
            </a>
        @endcan

        <hr class="border-secondary my-3">

        <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="bi bi-arrow-left-circle"></i> {{ __('Back to Site') }}
        </a>
    </nav>
</aside>
