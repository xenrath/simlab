<li class="menu-header">Dashboard</li>
<li class="{{ request()->is('peminjam/bidan') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/bidan') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li class="menu-header">Peminjaman</li>
<li class="{{ request()->is('peminjam/bidan/buat*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/bidan/buat') }}">
        <i class="fas fa-plus"></i>
        <span>Buat Peminjaman</span>
    </a>
</li>
<li class="{{ request()->is('peminjam/bidan/menunggu*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/bidan/menunggu') }}">
        <i class="fas fa-clock"></i>
        <span>Menunggu</span>
    </a>
</li>
<li class="{{ request()->is('peminjam/bidan/proses*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/bidan/proses') }}">
        <i class="fas fa-tasks"></i>
        <span>Dalam Peminjaman</span>
    </a>
</li>
<li class="{{ request()->is('peminjam/bidan/riwayat*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/bidan/riwayat') }}">
        <i class="fas fa-history"></i>
        <span>Riwayat</span>
    </a>
</li>
<li class="{{ request()->is('peminjam/bidan/tagihan*') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('peminjam/bidan/tagihan') }}">
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
{{-- <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
    <a href="{{ url('saran/create') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fas fa-rocket"></i> Saran / Masukan
    </a>
</div> --}}
