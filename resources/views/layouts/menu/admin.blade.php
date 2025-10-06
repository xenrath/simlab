<li class="menu-header">Dashboard</li>
<li class="{{ request()->is('admin') ? 'active' : '' }}">
    <a class="nav-link rounded-0" href="{{ url('admin') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li class="menu-header">Peminjaman</li>
<li class="{{ request()->is('admin/buat*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/buat') }}">
        <i class="fas fa-plus"></i>
        <span>Buat Peminjaman</span>
    </a>
</li>
<li class="{{ request()->is('admin/proses*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/proses') }}">
        <i class="fas fa-tasks"></i>
        <span>Dalam Peminjaman</span>
    </a>
</li>
<li class="{{ request()->is('admin/riwayat*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/riwayat') }}">
        <i class="fas fa-history"></i>
        <span>Riwayat</span>
    </a>
</li>
<li class="{{ request()->is('admin/tagihan*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/tagihan') }}">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Tagihan</span>
    </a>
</li>
<li class="menu-header">Pengguna</li>
<li class="{{ request()->is('admin/mahasiswa*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/mahasiswa') }}">
        <i class="fas fa-users"></i>
        <span>Mahasiswa</span>
    </a>
</li>
<li class="{{ request()->is('admin/laboran*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/laboran') }}">
        <i class="fas fa-users"></i>
        <span>Laboran</span>
    </a>
</li>
<li class="{{ request()->is('admin/tamu*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/tamu') }}">
        <i class="fas fa-users"></i>
        <span>Tamu</span>
    </a>
</li>
<li class="menu-header">Aset</li>
<li class="{{ request()->is('admin/barang/*') || request()->is('admin/barang') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/barang') }}">
        <i class="fas fa-cog"></i>
        <span>Barang</span>
    </a>
</li>
<li class="{{ request()->is('admin/bahan*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/bahan') }}">
        <i class="fas fa-cog"></i>
        <span>Bahan</span>
    </a>
</li>
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('admin/ruang*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/ruang') }}">
        <i class="fas fa-door-closed"></i>
        <span>Ruang</span>
    </a>
</li>
{{-- <li class="{{ request()->is('admin/barang-normal*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/barang-normal') }}">
    <i class="fas fa-cog"></i>
    <span>Barang Normal</span>
  </a>
</li> --}}
{{-- <li class="{{ request()->is('admin/barang-rusak*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/barang-rusak') }}">
        <i class="fas fa-cog"></i>
        <span>Barang Rusak</span>
    </a>
</li> --}}
{{-- <li class="menu-header">Tambah Stok</li>
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
</li> --}}
{{-- <li class="menu-header">Pengambilan</li>
<li class="{{ request()->is('admin/pengambilan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('admin/pengambilan') }}">
    <i class="fas fa-cog"></i>
    <span>Pengambilan Bahan</span>
  </a>
</li> --}}
