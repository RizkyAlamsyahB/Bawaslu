@auth
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="">STISLA</a>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <a href="">STISLA</a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Dashboard</li>
                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('dashboard') }}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                </li>
                @if (auth()->check() && auth()->user()->role === 'super_admin')
                <!-- Master Data Dropdown -->
                <li class="dropdown {{ Request::is('kecamatan', 'kelurahan', 'tps', 'user') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i><span>Master Data</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::is('kecamatan') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('kecamatan') }}">Kecamatan</a>
                        </li>
                        <li class="{{ Request::is('kelurahan') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('kelurahan') }}">Kelurahan</a>
                        </li>
                        <li class="{{ Request::is('tps') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('tps') }}">TPS</a>
                        </li>
                        <li class="{{ Request::is('user') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('user') }}">User</a>
                        </li>
                    </ul>
                </li>
            @endif


                <li class="menu-header">Starter</li>
                <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('blank-page') }}"><i class="far fa-square"></i> <span>Blank
                            Page</span></a>
                </li>
            </ul>
        </aside>
    </div>
@endauth
