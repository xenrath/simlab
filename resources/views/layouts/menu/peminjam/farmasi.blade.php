<li class="menu-header">Dashboard</li>
<li class="{{ request()->is('peminjam/farmasi') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/farmasi') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
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
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('peminjam/kuesioner*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/kuesioner') }}">
        <i class="fas fa-book"></i>
        <span>Kuesioner</span>
    </a>
</li>
<li class="{{ request()->is('peminjam/suratbebas*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/suratbebas') }}">
        <i class="fas fa-book"></i>
        <span>Surat Bebas</span>
    </a>
</li>