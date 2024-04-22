@if (auth()->user()->isLabTerpadu())
    <li class="menu-header">Peminjaman</li>
    <li class="{{ request()->is('peminjam/labterpadu/buat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/labterpadu/buat') }}">
            <i class="fas fa-plus"></i>
            <span>Buat Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/labterpadu/menunggu*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/labterpadu/menunggu') }}">
            <i class="fas fa-clock"></i>
            <span>Menunggu</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/labterpadu/proses*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/labterpadu/proses') }}">
            <i class="fas fa-tasks"></i>
            <span>Dalam Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/labterpadu/riwayat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/labterpadu/riwayat') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/labterpadu/tagihan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/labterpadu/tagihan') }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Tagihan</span>
        </a>
    </li>
@endif
@if (auth()->user()->isFarmasi())
    <li class="menu-header">Peminjaman</li>
    <li class="{{ request()->is('peminjam/farmasi/buat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/farmasi/buat') }}">
            <i class="fas fa-plus"></i>
            <span>Buat Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/menunggu*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/farmasi/menunggu') }}">
            <i class="fas fa-clock"></i>
            <span>Menunggu</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/proses*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/farmasi/proses') }}">
            <i class="fas fa-tasks"></i>
            <span>Dalam Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/riwayat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/farmasi/riwayat') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/tagihan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/farmasi/tagihan') }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Tagihan</span>
        </a>
    </li>
@endif
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('peminjam/kuesioner*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/kuesioner') }}">
        <i class="fas fa-book"></i>
        <span>Kuesioner</span>
    </a>
</li>
<li class="{{ request()->is('peminjam/suratbebas*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/suratbebas') }}">
        <i class="fas fa-book"></i>
        <span>Surat Bebas</span>
    </a>
</li>
{{-- <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
    <a href="{{ url('saran/create') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fas fa-rocket"></i> Saran / Masukan
    </a>
</div> --}}
