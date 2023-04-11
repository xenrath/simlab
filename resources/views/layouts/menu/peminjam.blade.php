@if (auth()->user()->subprodi->prodi_id == '4')
  <li class="menu-header">Mandiri</li>
  <li class="{{ request()->is('peminjam/normal/peminjaman*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/normal/peminjaman') }}">
      <i class="fas fa-list-alt"></i>
      <span>Peminjaman</span>
    </a>
  </li>
  <li class="{{ request()->is('peminjam/normal/pengembalian*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/normal/pengembalian') }}">
      <i class="fas fa-list-alt"></i>
      <span>Pengembalian</span>
    </a>
  </li>
  <li class="{{ request()->is('peminjam/normal/riwayat*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/normal/riwayat') }}">
      <i class="fas fa-list-alt"></i>
      <span>Riwayat</span>
    </a>
  </li>
  <li class="menu-header">Estafet</li>
  <li class="{{ request()->is('peminjam/estafet/peminjaman*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/estafet/peminjaman') }}">
      <i class="fas fa-list-alt"></i>
      <span>Peminjaman</span>
    </a>
  </li>
  <li class="{{ request()->is('peminjam/estafet/pengembalian*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/estafet/pengembalian') }}">
      <i class="fas fa-list-alt"></i>
      <span>Pengembalian</span>
    </a>
  </li>
  <li class="{{ request()->is('peminjam/estafet/riwayat*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/estafet/riwayat') }}">
      <i class="fas fa-list-alt"></i>
      <span>Riwayat</span>
    </a>
  </li>
@else
  <li class="menu-header">Peminjaman</li>
  <li class="{{ request()->is('peminjam/normal/peminjaman-new*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/normal/peminjaman-new') }}">
      <i class="fas fa-list-alt"></i>
      <span>Peminjaman</span>
    </a>
  </li>
  <li class="{{ request()->is('peminjam/normal/pengembalian-new*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/normal/pengembalian-new') }}">
      <i class="fas fa-list-alt"></i>
      <span>Pengembalian</span>
    </a>
  </li>
  <li class="{{ request()->is('peminjam/normal/riwayat-new*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('peminjam/normal/riwayat-new') }}">
      <i class="fas fa-list-alt"></i>
      <span>Riwayat</span>
    </a>
  </li>
@endif
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('peminjam/tagihan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('peminjam/tagihan') }}">
    <i class="fas fa-list-alt"></i>
    <span>Tagihan</span>
  </a>
</li>
<li class="{{ request()->is('peminjam/tatacara*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('peminjam/tatacara') }}">
    <i class="fas fa-book"></i>
    <span>Tata Cara</span>
  </a>
</li>
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
<div class="mt-4 mb-4 p-3 hide-sidebar-mini">
  <a href="{{ url('saran/create') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
    <i class="fas fa-rocket"></i> Saran / Masukan
  </a>
</div>
