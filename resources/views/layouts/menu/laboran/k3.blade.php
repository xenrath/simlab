<li class="menu-header">Dashboard</li>
<li class="{{ request()->is('laboran/k3') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/k3') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li class="menu-header">Peminjaman</li>
<li class="{{ request()->is('laboran/k3/peminjaman*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/k3/peminjaman') }}">
        <i class="fas fa-clock"></i>
        <span>Menunggu</span>
    </a>
</li>
<li class="{{ request()->is('laboran/k3/pengembalian*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/k3/pengembalian') }}">
        <i class="fas fa-tasks"></i>
        <span>Dalam Peminjaman</span>
    </a>
</li>
<li class="{{ request()->is('laboran/k3/riwayat*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/k3/riwayat') }}">
        <i class="fas fa-history"></i>
        <span>Riwayat</span>
    </a>
</li>
<li class="{{ request()->is('laboran/k3/tagihan*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/k3/tagihan') }}">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Tagihan</span>
    </a>
</li>
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('laboran/k3/laporan*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/k3/laporan') }}">
        <i class="fas fa-file-alt"></i>
        <span>Laporan</span>
    </a>
</li>
@if (auth()->user()->is_pengelola_bahan)
    <li class="{{ request()->is('laboran/k3/bahan*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('laboran/k3/bahan') }}">
            <i class="fas fa-vial"></i>
            <span>Bahan</span>
        </a>
    </li>
@endif
