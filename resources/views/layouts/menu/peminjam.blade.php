@if (auth()->user()->subprodi->prodi_id == '4')
    <li class="menu-header">Peminjaman</li>
    <li class="{{ request()->is('peminjam/farmasi/buat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/farmasi/buat') }}">
            <i class="fas fa-plus"></i>
            <span>Buat Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/menunggu*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/farmasi/menunggu') }}">
            <i class="fas fa-clock"></i>
            <span>Menunggu</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/proses*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/farmasi/proses') }}">
            <i class="fas fa-tasks"></i>
            <span>Dalam Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/riwayat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/farmasi/riwayat') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/farmasi/tagihan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/farmasi/tagihan') }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Tagihan</span>
        </a>
    </li>
    {{-- <li class="{{ request()->is('peminjam/normal/pengembalian*') ? 'active' : '' }}">
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
    </li> --}}
    {{-- <li class="menu-header">Estafet</li>
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
    </li> --}}
@else
    @if (auth()->user()->isBidan())
        <li class="menu-header">Peminjaman</li>
        <li class="{{ request()->is('peminjam/bidan/buat*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/bidan/buat') }}">
                <i class="fas fa-plus"></i>
                <span>Buat Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/bidan/menunggu*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/bidan/menunggu') }}">
                <i class="fas fa-clock"></i>
                <span>Menunggu</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/bidan/proses*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/bidan/proses') }}">
                <i class="fas fa-tasks"></i>
                <span>Dalam Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/bidan/riwayat*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/bidan/riwayat') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/bidan/tagihan*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/bidan/tagihan') }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Tagihan</span>
            </a>
        </li>
    @endif
    @if (auth()->user()->isPerawat())
        <li class="menu-header">Peminjaman</li>
        <li class="{{ request()->is('peminjam/perawat/buat*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/perawat/buat') }}">
                <i class="fas fa-plus"></i>
                <span>Buat Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/perawat/menunggu*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/perawat/menunggu') }}">
                <i class="fas fa-clock"></i>
                <span>Menunggu</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/perawat/proses*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/perawat/proses') }}">
                <i class="fas fa-tasks"></i>
                <span>Dalam Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/perawat/riwayat*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/perawat/riwayat') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/perawat/tagihan*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/perawat/tagihan') }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Tagihan</span>
            </a>
        </li>
    @endif
    @if (auth()->user()->isK3())
        <li class="menu-header">Peminjaman</li>
        <li class="{{ request()->is('peminjam/k3/buat*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/k3/buat') }}">
                <i class="fas fa-plus"></i>
                <span>Buat Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/k3/menunggu*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/k3/menunggu') }}">
                <i class="fas fa-clock"></i>
                <span>Menunggu</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/k3/proses*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/k3/proses') }}">
                <i class="fas fa-tasks"></i>
                <span>Dalam Peminjaman</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/k3/riwayat*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/k3/riwayat') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
            </a>
        </li>
        <li class="{{ request()->is('peminjam/k3/tagihan*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('peminjam/k3/tagihan') }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Tagihan</span>
            </a>
        </li>
    @endif
    {{-- <li class="menu-header">Peminjaman</li>
    <li class="{{ request()->is('peminjam/normal/peminjaman-new*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/normal/peminjaman-new') }}">
            <i class="fas fa-list-alt"></i>
            <span>Peminjaman</span>
        </a>
    </li>
    <li class="{{ request()->is('peminjam/tagihan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('peminjam/tagihan') }}">
            <i class="fas fa-list-alt"></i>
            <span>Tagihan</span>
        </a>
    </li> --}}
    {{-- <li class="{{ request()->is('peminjam/normal/pengembalian-new*') ? 'active' : '' }}">
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
    </li> --}}
@endif
<li class="menu-header">Lainnya</li>
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
