<nav class="navbar navbar-expand-md bg-body border-bottom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center text-primary" href="{{ route('dashboard') }}">
            <x-application-logo style="height: 2rem; width: auto; fill: currentColor;" />
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </li>
                @can('dashboard.view')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('Admin Panel') }}</a>
                    </li>
                @endcan
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <button class="btn btn-link nav-link dropdown-toggle text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ __('Log Out') }}</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
