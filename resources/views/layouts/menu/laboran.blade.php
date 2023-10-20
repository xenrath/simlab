{{-- <li class="menu-header">Bahan</li>
<li class="{{ request()->is('laboran/bahan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('laboran/bahan') }}">
    <i class="fas fa-cog"></i>
    <span>Daftar Bahan</span>
  </a>
</li> --}}
@if (auth()->user()->ruangs->first()->tempat_id == '2')
    <li class="menu-header">Mandiri</li>
    <li class="{{ request()->is('laboran/peminjaman*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/peminjaman') }}">
            <i class="fas fa-cog"></i>
            <span>Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('laboran/pengembalian*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/pengembalian') }}">
            <i class="fas fa-cog"></i>
            <span>Pengembalian</span>
        </a>
    </li>
    <li class="{{ request()->is('laboran/riwayat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/riwayat') }}">
            <i class="fas fa-cog"></i>
            <span>Riwayat</span>
        </a>
    </li>
    <li class="menu-header">Estafet</li>
    <li class="{{ request()->is('laboran/kelompok/peminjaman*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/kelompok/peminjaman') }}">
            <i class="fas fa-cog"></i>
            <span>Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('laboran/kelompok/pengembalian*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/kelompok/pengembalian') }}">
            <i class="fas fa-cog"></i>
            <span>Pengembalian</span>
        </a>
    </li>
    <li class="{{ request()->is('laboran/kelompok/riwayat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/kelompok/riwayat') }}">
            <i class="fas fa-cog"></i>
            <span>Riwayat</span>
        </a>
    </li>
@else
    <li class="menu-header">Peminjaman</li>
    <li
        class="dropdown {{ request()->is('laboran/peminjaman-new*') || request()->is('laboran/pengembalian-new*') || request()->is('laboran/riwayat-new*') ? 'active' : '' }}">
        <a class="nav-link has-dropdown" data-toggle="dropdown" href="{{ url('laboran/peminjaman-new') }}">
            <i class="fas fa-clipboard-list"></i>
            <span>Data Peminjaman</span>
        </a>
        <ul class="dropdown-menu">
            <li class="{{ request()->is('laboran/peminjaman-new*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('laboran/peminjaman-new') }}">Menunggu</a>
            </li>
            <li class="{{ request()->is('laboran/pengembalian-new*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('laboran/pengembalian-new') }}">Dalam Peminjaman</a>
            </li>
            <li class="{{ request()->is('laboran/riwayat-new*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('laboran/riwayat-new') }}">Riwayat</a>
            </li>
        </ul>
    </li>
    <li class="{{ request()->is('laboran/tagihan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/tagihan') }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Data Tagihan</span>
        </a>
    </li>
@endif
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('laboran/laporan*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('laboran/laporan') }}">
        <i class="fas fa-cog"></i>
        <span>Laporan</span>
    </a>
</li>
