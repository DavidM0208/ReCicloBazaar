<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            {{ config('app.name', 'ReCicloBazaar') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                @auth
                    @can('ver usuarios')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                            href="{{ route('admin.users') }}">
                                <i class="bi bi-people"></i> Gestión de Usuarios
                            </a>
                        </li>
                    @endcan

                    @can('ver roles')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }}"
                            href="{{ route('admin.roles') }}">
                                <i class="bi bi-shield-lock"></i> Gestión de Roles
                            </a>
                        </li>
                    @endcan

                    @can('gestionar permisos')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.permissions*') ? 'active' : '' }}"
                            href="{{ route('admin.permissions') }}">
                                <i class="bi bi-key"></i> Gestión de Permisos
                            </a>
                        </li>
                    @endcan
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            <span class="badge bg-info ms-1">
                                {{ Auth::user()->roles->first()->name ?? 'Sin rol' }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
