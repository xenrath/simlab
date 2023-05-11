<li class="menu-header">Peminjaman</li>
<li class="{{ request()->is('admin/peminjaman*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/peminjaman') }}">
    <i class="fas fa-cog"></i>
    <span>Data Peminjaman</span>
  </a>
</li>
<li class="{{ request()->is('admin/tagihan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/tagihan') }}">
    <i class="fas fa-cog"></i>
    <span>Data Tagihan</span>
  </a>
</li>
<li class="menu-header">User</li>
<li class="{{ request()->is('admin/user*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/user') }}">
    <i class="fas fa-cog"></i>
    <span>Data User</span>
  </a>
</li>
<li class="menu-header">Barang</li>
<li class="{{ request()->is('admin/barang/*') || request()->is('admin/barang') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/barang') }}">
    <i class="fas fa-cog"></i>
    <span>Data Barang</span>
  </a>
</li>
{{-- <li class="{{ request()->is('admin/barang-normal*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/barang-normal') }}">
    <i class="fas fa-cog"></i>
    <span>Barang Normal</span>
  </a>
</li> --}}
<li class="{{ request()->is('admin/barang-rusak*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/barang-rusak') }}">
    <i class="fas fa-cog"></i>
    <span>Barang Rusak</span>
  </a>
</li>
<li class="menu-header">Bahan</li>
<li class="{{ request()->is('admin/bahan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/bahan') }}">
    <i class="fas fa-cog"></i>
    <span>Data Bahan</span>
  </a>
</li>
<li class="menu-header">Tambah Stok</li>
<li class="{{ request()->is('admin/stokbarang*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/stokbarang') }}">
    <i class="fas fa-cog"></i>
    <span>Stok Barang</span>
  </a>
</li>
<li class="{{ request()->is('admin/stokbahan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/stokbahan') }}">
    <i class="fas fa-cog"></i>
    <span>Stok Bahan</span>
  </a>
</li>
{{-- <li class="menu-header">Pengambilan</li>
<li class="{{ request()->is('admin/pengambilan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/pengambilan') }}">
    <i class="fas fa-cog"></i>
    <span>Pengambilan Bahan</span>
  </a>
</li> --}}
