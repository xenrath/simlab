@if (auth()->user()->id == 14)
    <li class="menu-header">Dashboard</li>
    <li class="{{ request()->is('laboran/farmasi') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('laboran/farmasi') }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="menu-header">Peminjaman</li>
    <li class="{{ request()->is('laboran/farmasi/peminjaman*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('laboran/farmasi/peminjaman') }}">
            <i class="fas fa-clock"></i>
            <span>Menunggu</span>
        </a>
    </li>
    <li class="{{ request()->is('laboran/farmasi/pengembalian*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('laboran/farmasi/pengembalian') }}">
            <i class="fas fa-tasks"></i>
            <span>Dalam Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('laboran/farmasi/riwayat*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('laboran/farmasi/riwayat') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat</span>
        </a>
    </li>
    <li class="{{ request()->is('laboran/farmasi/tagihan*') ? 'active' : '' }}">
        <a class="nav-link rounded-0" href="{{ url('laboran/farmasi/tagihan') }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Tagihan</span>
        </a>
    </li>
    @if (auth()->user()->is_pengelola_bahan)
        <li class="menu-header">Lainnya</li>
        <li class="{{ request()->is('laboran/farmasi/bahan*') ? 'active' : '' }}">
            <a class="nav-link rounded-0" href="{{ url('laboran/farmasi/bahan') }}">
                <i class="fas fa-vial"></i>
                <span>Bahan</span>
            </a>
        </li>
    @endif
@endif
