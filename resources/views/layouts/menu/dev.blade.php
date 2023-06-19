<li class="menu-header">Peminjaman</li>
<li class="{{ request()->is('dev/peminjaman*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/peminjaman') }}">
    <i class="fas fa-cog"></i>
    <span>Data Peminjaman</span>
  </a>
</li>
<li class="menu-header">User</li>
<li class="{{ request()->is('dev/user*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/user') }}">
    <i class="fas fa-cog"></i>
    <span>Data User</span>
  </a>
</li>
{{-- <li class="{{ request()->is('dev/peminjam*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/peminjam') }}">
    <i class="fas fa-cog"></i>
    <span>Data Peminjam</span>
  </a>
</li> --}}
<li class="menu-header">Prodi</li>
<li class="{{ request()->is('dev/prodi*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/prodi') }}">
    <i class="fas fa-cog"></i>
    <span>Data Prodi</span>
  </a>
</li>
<li class="{{ request()->is('dev/subprodi*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/subprodi') }}">
    <i class="fas fa-cog"></i>
    <span>Data Sub Prodi</span>
  </a>
</li>
<li class="menu-header">Tempat & Ruang</li>
<li class="{{ request()->is('dev/tempat*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/tempat') }}">
    <i class="fas fa-cog"></i>
    <span>Data Tempat</span>
  </a>
</li>
<li class="{{ request()->is('dev/ruang*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/ruang') }}">
    <i class="fas fa-cog"></i>
    <span>Data Ruang</span>
  </a>
</li>
<li class="menu-header">Barang & Bahan</li>
<li class="{{ request()->is('dev/barang*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/barang') }}">
    <i class="fas fa-cog"></i>
    <span>Data Barang</span>
  </a>
</li>
<li class="{{ request()->is('dev/bahan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/bahan') }}">
    <i class="fas fa-cog"></i>
    <span>Data Bahan</span>
  </a>
</li>
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('dev/kuesioner*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/kuesioner') }}">
    <i class="fas fa-cog"></i>
    <span>Data Kuesioner</span>
  </a>
</li>
<li class="{{ request()->is('dev/praktik*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/praktik') }}">
    <i class="fas fa-cog"></i>
    <span>Data Praktik</span>
  </a>
</li>
<li class="{{ request()->is('saran*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('saran') }}">
    <i class="fas fa-cog"></i>
    <span>Data Saran</span>
  </a>
</li>
<li class="{{ request()->is('dev/satuan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('dev/satuan') }}">
    <i class="fas fa-cog"></i>
    <span>Data Satuan</span>
  </a>
</li>
