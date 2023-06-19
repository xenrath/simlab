<li class="menu-header">Data Master</li>
<li class="{{ request()->is('kalab/admin*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/admin') }}">
    <i class="fas fa-cog"></i>
    <span>Admin</span>
  </a>
</li>
<li class="{{ request()->is('kalab/laboran*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/laboran') }}">
    <i class="fas fa-cog"></i>
    <span>Laboran</span>
  </a>
</li>
<li class="{{ request()->is('kalab/peminjam*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/peminjam') }}">
    <i class="fas fa-cog"></i>
    <span>Peminjam</span>
  </a>
</li>
<li class="{{ request()->is('kalab/ruang*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/ruang') }}">
    <i class="fas fa-cog"></i>
    <span>Ruang</span>
  </a>
</li>
<li class="menu-header">Laporan</li>
<li class="{{ request()->is('kalab/grafik/pengunjung*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/grafik/pengunjung') }}">
    <i class="fas fa-cog"></i>
    <span>Grafik Pengunjung</span>
  </a>
</li>
<li class="{{ request()->is('kalab/grafik/ruang*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/grafik/ruang') }}">
    <i class="fas fa-cog"></i>
    <span>Grafik Ruang</span>
  </a>
</li>
<li class="{{ request()->is('kalab/grafik/barang*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/grafik/barang') }}">
    <i class="fas fa-cog"></i>
    <span>Grafik Barang</span>
  </a>
</li>
<li class="menu-header">Pemasukan</li>
<li class="{{ request()->is('kalab/stokbarang*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/stokbarang') }}">
    <i class="fas fa-cog"></i>
    <span>Barang Masuk</span>
  </a>
</li>
<li class="{{ request()->is('kalab/stokbahan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/stokbahan') }}">
    <i class="fas fa-cog"></i>
    <span>Bahan Masuk</span>
  </a>
</li>
<li class="menu-header">Rusak | Hilang | Habis</li>
<li class="{{ request()->is('kalab/barangrusak*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/barangrusak') }}">
    <i class="fas fa-cog"></i>
    <span>Barang Rusak</span>
  </a>
</li>
<li class="{{ request()->is('kalab/baranghilang*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/baranghilang') }}">
    <i class="fas fa-cog"></i>
    <span>Barang Hilang</span>
  </a>
</li>
<li class="{{ request()->is('kalab/bahanhabis*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/bahanhabis') }}">
    <i class="fas fa-cog"></i>
    <span>Bahan Habis</span>
  </a>
</li>
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('kalab/kuesioner*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/kuesioner') }}">
    <i class="fas fa-cog"></i>
    <span>Data Kuesioner</span>
  </a>
</li>
<li class="{{ request()->is('kalab/absen*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('kalab/absen') }}">
    <i class="fas fa-cog"></i>
    <span>Data Kunjungan</span>
  </a>
</li>
