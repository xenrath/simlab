<li class="menu-header">Peminjaman</li>
<li class="{{ request()->is('admin/peminjaman/create*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/peminjaman/create') }}">
        <i class="fas fa-plus"></i>
        <span>Buat Peminjaman</span>
    </a>
</li>
<li class="{{ request()->is('admin/peminjaman/proses*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/peminjaman/proses') }}">
        <i class="fas fa-tasks"></i>
        <span>Dalam Peminjaman</span>
    </a>
</li>
<li class="{{ request()->is('admin/peminjaman/selesai*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/peminjaman/selesai') }}">
        <i class="fas fa-history"></i>
        <span>Riwayat</span>
    </a>
</li>
<li class="{{ request()->is('admin/peminjaman/tagihan*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/peminjaman/tagihan') }}">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Tagihan</span>
    </a>
</li>
<li class="menu-header">Pengguna</li>
<li class="{{ request()->is('admin/pengguna/mahasiswa*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/pengguna/mahasiswa') }}">
        <i class="fas fa-users"></i>
        <span>Mahasiswa</span>
    </a>
</li>
<li class="{{ request()->is('admin/pengguna/laboran*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/pengguna/laboran') }}">
        <i class="fas fa-users"></i>
        <span>Laboran</span>
    </a>
</li>
<li class="{{ request()->is('admin/pengguna/tamu*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('admin/pengguna/tamu') }}">
        <i class="fas fa-users"></i>
        <span>Tamu</span>
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
