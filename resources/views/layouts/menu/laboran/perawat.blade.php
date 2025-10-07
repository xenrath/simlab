<li class="menu-header">Dashboard</li>
<li class="{{ request()->is('laboran/perawat') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/perawat') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li class="menu-header">Peminjaman</li>
<li class="{{ request()->is('laboran/perawat/peminjaman*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/perawat/peminjaman') }}">
        <i class="fas fa-clock"></i>
        <span>Menunggu</span>
    </a>
</li>
<li class="{{ request()->is('laboran/perawat/pengembalian*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/perawat/pengembalian') }}">
        <i class="fas fa-tasks"></i>
        <span>Dalam Peminjaman</span>
    </a>
</li>
<li class="{{ request()->is('laboran/perawat/riwayat*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/perawat/riwayat') }}">
        <i class="fas fa-history"></i>
        <span>Riwayat</span>
    </a>
</li>
<li class="{{ request()->is('laboran/perawat/tagihan*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/perawat/tagihan') }}">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Tagihan</span>
    </a>
</li>
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('laboran/perawat/laporan*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('laboran/perawat/laporan') }}">
        <i class="fas fa-file-alt"></i>
        <span>Laporan</span>
    </a>
</li>
@if (auth()->user()->is_pengelola_bahan)
    <li class="{{ request()->is('laboran/perawat/bahan*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('laboran/perawat/bahan') }}">
            <i class="fas fa-vial"></i>
            <span>Bahan</span>
        </a>
    </li>
@endif
