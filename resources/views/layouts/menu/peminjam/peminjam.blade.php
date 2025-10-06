<li class="menu-header">Dashboard</li>
<li class="{{ request()->is('peminjam/labterpadu') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/labterpadu') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
@if (auth()->user()->isLabTerpadu())
    @if (auth()->user()->isFeb())
        <li class="menu-header">Peminjaman</li>
        <li class="{{ request()->is('peminjam/feb/buat*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/feb/buat') }}">
                <i class="fas fa-plus"></i>
                <span>Buat Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/feb/menunggu*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/feb/menunggu') }}">
                <i class="fas fa-clock"></i>
                <span>Menunggu</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/feb/proses*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/feb/proses') }}">
                <i class="fas fa-tasks"></i>
                <span>Dalam Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/feb/riwayat*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/feb/riwayat') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
            </a>
        </li>
    @elseif (auth()->user()->isTi())
        <li class="menu-header">Peminjaman</li>
        <li class="{{ request()->is('peminjam/ti/buat*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/ti/buat') }}">
                <i class="fas fa-plus"></i>
                <span>Buat Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/ti/menunggu*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/ti/menunggu') }}">
                <i class="fas fa-clock"></i>
                <span>Menunggu</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/ti/proses*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/ti/proses') }}">
                <i class="fas fa-tasks"></i>
                <span>Dalam Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/ti/riwayat*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/ti/riwayat') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
            </a>
        </li>
    @else
        <li class="menu-header">Peminjaman</li>
        <li class="{{ request()->is('peminjam/labterpadu/buat*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/labterpadu/buat') }}">
                <i class="fas fa-plus"></i>
                <span>Buat Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/labterpadu/menunggu*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/labterpadu/menunggu') }}">
                <i class="fas fa-clock"></i>
                <span>Menunggu</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/labterpadu/proses*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/labterpadu/proses') }}">
                <i class="fas fa-tasks"></i>
                <span>Dalam Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/labterpadu/riwayat*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/labterpadu/riwayat') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/labterpadu/tagihan*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('peminjam/labterpadu/tagihan') }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Tagihan</span>
            </a>
        </li>
    @endif
@endif
@if (auth()->user()->isFarmasi())
    <li class="menu-header">Peminjaman</li>
    <li class="{{ request()->is('peminjam/farmasi/buat*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('peminjam/farmasi/buat') }}">
            <i class="fas fa-plus"></i>
            <span>Buat Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/menunggu*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('peminjam/farmasi/menunggu') }}">
            <i class="fas fa-clock"></i>
            <span>Menunggu</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/proses*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('peminjam/farmasi/proses') }}">
            <i class="fas fa-tasks"></i>
            <span>Dalam Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/riwayat*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('peminjam/farmasi/riwayat') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/tagihan*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('peminjam/farmasi/tagihan') }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Tagihan</span>
        </a>
    </li>
@endif
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('peminjam/kuesioner*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/kuesioner') }}">
        <i class="fas fa-book"></i>
        <span>Kuesioner</span>
    </a>
</li>
@if (!auth()->user()->isFeb() && !auth()->user()->isTi())
    <li class="{{ request()->is('peminjam/suratbebas*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('peminjam/suratbebas') }}">
            <i class="fas fa-book"></i>
            <span>Surat Bebas</span>
        </a>
    </li>
@endif
{{-- <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
    <a href="{{ url('saran/create') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fas fa-rocket"></i> Saran / Masukan
    </a>
</div> --}}
