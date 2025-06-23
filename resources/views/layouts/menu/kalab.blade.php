<li class="menu-header">Menu 1</li>
<li class="{{ request()->is('kalab/laboran*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/laboran') }}">
        <i class="fas fa-users"></i>
        <span>Laboran</span>
    </a>
</li>
<li class="{{ request()->is('kalab/mahasiswa*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/mahasiswa') }}">
        <i class="fas fa-users"></i>
        <span>Mahasiswa</span>
    </a>
</li>
<li class="{{ request()->is('kalab/tamu*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/tamu') }}">
        <i class="fas fa-users"></i>
        <span>Tamu</span>
    </a>
</li>
<li class="menu-header">Menu 2</li>
<li class="{{ request()->is('kalab/barang*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/barang') }}">
        <i class="fas fa-stethoscope"></i>
        <span>Barang</span>
    </a>
</li>
<li class="{{ request()->is('kalab/bahan*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/bahan') }}">
        <i class="fas fa-pills"></i>
        <span>Bahan</span>
    </a>
</li>
<li class="{{ request()->is('kalab/ruang*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/ruang') }}">
        <i class="fas fa-columns"></i>
        <span>Ruang</span>
    </a>
</li>
<li class="menu-header">Menu 3</li>
<li class="{{ request()->is('kalab/grafik-pengunjung*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/grafik-pengunjung') }}">
        <i class="fas fa-chart-bar"></i>
        <span>Grafik Pengunjung</span>
    </a>
</li>
<li class="{{ request()->is('kalab/grafik-ruang*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/grafik-ruang') }}">
        <i class="fas fa-chart-bar"></i>
        <span>Grafik Ruang</span>
    </a>
</li>
<li class="{{ request()->is('kalab/grafik-barang*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/grafik-barang') }}">
        <i class="fas fa-chart-bar"></i>
        <span>Grafik Barang</span>
    </a>
</li>
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('kalab/kuesioner*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/kuesioner') }}">
        <i class="fas fa-cog"></i>
        <span>Kuesioner</span>
    </a>
</li>
<li class="{{ request()->is('kalab/absen*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/absen') }}">
        <i class="fas fa-cog"></i>
        <span>Kunjungan</span>
    </a>
</li>
<li class="{{ request()->is('kalab/arsip*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('kalab/arsip') }}">
        <i class="fas fa-archive"></i>
        <span>Arsip</span>
    </a>
</li>
