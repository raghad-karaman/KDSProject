<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder">Afet</span>
        </a>
    </div>

    <ul class="menu-inner py-1">

        <li class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <!-- Mevcut Kaynaklar -->
        <li class="menu-item">
            <a href="{{ route('admin.resources.index') }}" class="menu-link">
                <i class="menu-icon bx bx-package"></i>
                <div>Mevcut Kaynaklar</div>
            </a>
        </li>

        <li class="menu-item">
            <a class="menu-link" href="{{ route('admin.needs.index') }}">
                <i class="menu-icon bx bx-list-check"></i>
                <div>İhtiyaçlar</div>
            </a>
        </li>

        <li class="menu-item">
            <a class="menu-link" href="{{ route('admin.relief-teams.index') }}">
                <i class="menu-icon bx bx-group"></i>
                <div>Ekipler</div>
            </a>
        </li>

        <li class="menu-item">
            <a class="menu-link" href="{{ route('admin.victims.index') }}">
                <i class="menu-icon bx bx-user"></i>
                <div>Mağdurlar</div>
            </a>
        </li>

        <li class="menu-item">
            <a class="menu-link" href="{{ route('admin.regions.index') }}">
                <i class="menu-icon bx bx-map"></i>
                <div>Bölgeler</div>
            </a>
        </li>
        <li class="menu-item">
    <a class="menu-link" href="{{ route('admin.distributions.index') }}">
        <i class="menu-icon bx bx-transfer"></i>
        <div>Dağıtımlar</div>
    </a>
</li>

     <li class="menu-item">
    <a class="menu-link" href="{{ route('admin.reports.index') }}">
        <i class="menu-icon bx bx-file"></i>
        <div>Raporlar</div>
    </a>
</li>

       <li class="menu-item">
    <a class="menu-link" href="{{ route('admin.notifications.index') }}">
        <i class="menu-icon bx bx-bell"></i>
        <div>Bildirimler</div>
    </a>
</li>


    </ul>
</aside>
