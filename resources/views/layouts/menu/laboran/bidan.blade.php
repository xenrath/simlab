<li class="menu-header">Dashboard</li>
<li class="{{ request()->is('laboran/bidan') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/bidan') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li class="menu-header">Peminjaman</li>
<li class="{{ request()->is('laboran/bidan/peminjaman*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/bidan/peminjaman') }}">
        <i class="fas fa-clock"></i>
        <span>Menunggu</span>
    </a>
</li>
<li class="{{ request()->is('laboran/bidan/pengembalian*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/bidan/pengembalian') }}">
        <i class="fas fa-tasks"></i>
        <span>Dalam Peminjaman</span>
    </a>
</li>
<li class="{{ request()->is('laboran/bidan/riwayat*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/bidan/riwayat') }}">
        <i class="fas fa-history"></i>
        <span>Riwayat</span>
    </a>
</li>
<li class="{{ request()->is('laboran/bidan/tagihan*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/bidan/tagihan') }}">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Tagihan</span>
    </a>
</li>
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('laboran/bidan/laporan*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/bidan/laporan') }}">
        <i class="fas fa-file-alt"></i>
        <span>Laporan</span>
    </a>
</li>
@if (auth()->user()->is_pengelola_bahan)
    <li class="{{ request()->is('laboran/bidan/bahan*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('laboran/bidan/bahan') }}">
            <i class="fas fa-vial"></i>
            <span>Bahan</span>
        </a>
    </li>
@endif
