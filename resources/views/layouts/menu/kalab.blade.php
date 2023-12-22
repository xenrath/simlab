<li class="menu-header">Data Pengguna</li>
<li class="{{ request()->is('kalab/laboran*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/laboran') }}">
        <i class="fas fa-users"></i>
        <span>Data Laboran</span>
    </a>
</li>
<li class="{{ request()->is('kalab/peminjam*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/peminjam') }}">
        <i class="fas fa-users"></i>
        <span>Data Mahasiswa</span>
    </a>
</li>
<li class="{{ request()->is('kalab/tamu*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/tamu') }}">
        <i class="fas fa-users"></i>
        <span>Data Tamu</span>
    </a>
</li>
{{-- <li class="{{ request()->is('kalab/ruang*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/ruang') }}">
        <i class="fas fa-columns"></i>
        <span>Data Ruang</span>
    </a>
</li> --}}
<li class="menu-header">Data Grafik</li>
<li class="{{ request()->is('kalab/grafik/pengunjung*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/grafik/pengunjung') }}">
        <i class="fas fa-chart-bar"></i>
        <span>Grafik Pengunjung</span>
    </a>
</li>
<li class="{{ request()->is('kalab/grafik/ruang*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/grafik/ruang') }}">
        <i class="fas fa-chart-bar"></i>
        <span>Grafik Ruang</span>
    </a>
</li>
<li class="{{ request()->is('kalab/grafik/barang*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/grafik/barang') }}">
        <i class="fas fa-chart-bar"></i>
        <span>Grafik Barang</span>
    </a>
</li>
<li
    class="dropdown {{ request()->is('kalab/grafik/pengunjung') || request()->is('kalab/grafik/ruang') || request()->is('kalab/grafik/barang') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown">
        <i class="fas fa-chart-bar"></i>
        <span>Data Grafik</span>
    </a>
    <ul class="dropdown-menu">
        <li class="{{ request()->is('kalab/grafik/pengunjung*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('kalab/grafik/pengunjung') }}">Grafik Pengunjung</a>
        </li>
        <li class="{{ request()->is('kalab/grafik/ruang*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('kalab/grafik/ruang') }}">Grafik Ruang</a>
        </li>
        <li class="{{ request()->is('kalab/grafik/barang*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('kalab/grafik/barang') }}">Grafik Barang</a>
        </li>
    </ul>
</li>
<li
    class="dropdown {{ request()->is('kalab/grafik/stokbarang') || request()->is('kalab/grafik/stokbahan') || request()->is('kalab/grafik/barang') ? 'active' : '' }}">
    <a href="#" class="nav-link has-dropdown">
        <i class="fas fa-chart-bar"></i>
        <span>Data Masuk</span>
    </a>
    <ul class="dropdown-menu">
        <li class="{{ request()->is('kalab/stokbarang*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('kalab/stokbarang') }}">Barang Masuk</a>
        </li>
        <li class="{{ request()->is('kalab/stokbahan*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('kalab/stokbahan') }}">Bahan Masuk</a>
        </li>
    </ul>
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
<li class="{{ request()->is('kalab/arsip*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/arsip') }}">
        <i class="fas fa-archive"></i>
        <span>Data Arsip</span>
    </a>
</li>
