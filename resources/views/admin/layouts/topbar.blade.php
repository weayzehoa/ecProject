<nav id="topbar" class="main-header navbar navbar-expand navbar-dark bg-navy">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-user mr-2"></i><span class="d-none d-md-inline">{{ Auth::user()->name ?? '' }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ Auth::user()->email ?? '' }}</span>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.admins.show',Auth::user()->id ) }}" class="dropdown-item">
                    <i class="fas fa-cog mr-2"></i><span>個人資料變更</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.logout') }}" class="dropdown-item">
                    <i class="fas fa-door-open text-danger mr-2"></i><span>登出 (Logout)</span>
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a href="#" title="縮小側邊選單" class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <a href="javascript:" id="fullscreen-button" title="擴展成全螢幕" class="nav-link" data-widget="fullscreen" data-slide="true" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
