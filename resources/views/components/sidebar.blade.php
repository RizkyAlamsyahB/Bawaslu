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
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i><span>Master
                                Data</span></a>
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

                            <li class="{{ Request::is('tipe_pemilihan') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('tipe_pemilihan') }}">Tipe Pemilihan</a>
                            </li>

                            <li class="{{ Request::is('pasangan_calon') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ url('pasangan_calon') }}">Pasangan Calon</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (auth()->user()->role === 'tps' || auth()->user()->role === 'super_admin')
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link has-dropdown">
                            <i class="fas fa-vote-yea"></i> <span>Rekapitulasi</span>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($tipePemilihans as $tipe)
                                <li class="{{ Request::is('rekapitulasi/' . $tipe->id) ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('rekapitulasi.create', $tipe->id) }}">
                                        {{ $tipe->nama }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                
            </ul>
        </aside>
    </div>
@endauth
