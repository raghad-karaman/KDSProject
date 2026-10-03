<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">

    <div class="navbar-nav-right d-flex align-items-center">

        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4"></i>
                <input type="text" class="form-control border-0 shadow-none"
                       placeholder="Search..." />
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" class="w-px-40 rounded-circle" />
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-user me-2"></i> Profil
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="dropdown-item" type="submit">
            <i class="bx bx-power-off me-2"></i> Çıkış Yap
        </button>
    </form>
</li>

                </ul>
            </li>

        </ul>

    </div>
</nav>
