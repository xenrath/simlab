{{-- <li class="menu-header">Bahan</li>
<li class="{{ request()->is('laboran/bahan*') ? 'active' : '' }}">
  <a class="nav-link" href="{{ url('laboran/bahan') }}">
    <i class="fas fa-cog"></i>
    <span>Daftar Bahan</span>
  </a>
</li> --}}
@if (auth()->user()->ruangs->first()->tempat_id == '2')
    @if (auth()->user()->id == 14)
        <li class="menu-header">Peminjaman</li>
        <li class="{{ request()->is('laboran/peminjaman*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('laboran/peminjaman') }}">
                <i class="fas fa-clock"></i>
                <span>Menunggu</span>
            </a>
        </li>
        <li class="{{ request()->is('laboran/pengembalian*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('laboran/pengembalian') }}">
                <i class="fas fa-tasks"></i>
                <span>Dalam Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('laboran/riwayat*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('laboran/riwayat') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
            </a>
        </li>
        <li class="{{ request()->is('laboran/tagihan*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('laboran/tagihan') }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Tagihan</span>
            </a>
        </li>
    @endif
@else
    <li class="menu-header">Peminjaman</li>
    <li class="{{ request()->is('laboran/peminjaman-new*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/peminjaman-new') }}">
            <i class="fas fa-clock"></i>
            <span>Menunggu</span>
        </a>
    </li>
    <li class="{{ request()->is('laboran/pengembalian-new*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/pengembalian-new') }}">
            <i class="fas fa-tasks"></i>
            <span>Dalam Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('laboran/riwayat-new*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/riwayat-new') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat</span>
        </a>
    </li>
    <li class="{{ request()->is('laboran/tagihan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('laboran/tagihan') }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Tagihan</span>
        </a>
    </li>
@endif
<li class="menu-header">Lainnya</li>
<li class="{{ request()->is('laboran/laporan*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('laboran/laporan') }}">
        <i class="fas fa-cog"></i>
        <span>Laporan</span>
    </a>
</li>
