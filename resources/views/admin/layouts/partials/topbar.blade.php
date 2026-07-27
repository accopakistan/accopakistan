<header class="admin-topbar d-flex align-items-center justify-content-between px-3 py-2">
    <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" data-sidebar-toggle>
        <i class="bi bi-list"></i>
    </button>

    <div class="d-none d-lg-block"></div>

    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-outline-secondary" type="button" data-theme-toggle title="{{ __('Toggle dark mode') }}">
            <i class="bi bi-circle-half"></i>
        </button>

        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ auth()->user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">{{ __('Log Out') }}</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
