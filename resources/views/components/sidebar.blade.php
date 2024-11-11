@auth
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="">REKAPITULASI</a>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <a href="">REKAPITULASI</a>
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

             

                <!-- Menu Rekapitulasi -->
                <li class="dropdown {{ Request::is('rekapitulasi*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-chart-line"></i><span>Rekapitulasi</span></a>
                    <ul class="dropdown-menu">
                    @if (auth()->user()->role === 'tps' || auth()->user()->role === 'super_admin')
    <li class="{{ Request::is('jumlah_data_pemilih') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('jumlah_data_pemilih') }}">Rekapitulasi Jumlah Data Pemilih</a>
    </li>
@endif

                        @if (auth()->user()->role === 'kelurahan')
                            <li class="{{ Request::is('rekapitulasi/kelurahan') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('rekapitulasi/kelurahan') }}">Rekapitulasi Kelurahan</a>
                            </li>
                        @endif
                        @if (auth()->user()->role === 'kecamatan')
                            <li class="{{ Request::is('rekapitulasi/kecamatan') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('rekapitulasi/kecamatan') }}">Rekapitulasi Kecamatan</a>
                            </li>
                        @endif
                        @if (auth()->user()->role === 'kota')
                            <li class="{{ Request::is('rekapitulasi/kota') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('rekapitulasi/kota') }}">Rekapitulasi Kota</a>
                            </li>
                        @endif
                        @if (auth()->user()->role === 'super_admin')
                            <li class="{{ Request::is('rekapitulasi/super-admin') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('rekapitulasi/super-admin') }}">Rekapitulasi Super Admin</a>
                            </li>
                        @endif
                    </ul>
                </li>
            </ul>
        </aside>
    </div>
@endauth
