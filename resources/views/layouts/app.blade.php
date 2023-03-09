<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Sistem Informasi Management Laboratoritum - Universitas Bhamada Slawi">
  <meta name="keywords"
    content="Simlab, Bhamada, Laboratorium, Peminjaman, Simlab Bhamada, Simlab Universitas Bhamada, Universitas Bhamada, Peminjaman Alat Laboratorium, Sistem Peminjaman Alat Lab">
  <meta name="author" content="IT Bhamada">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

  <title>@yield('title')</title>

  <!-- Icon -->
  <link rel="icon" href="{{ asset('storage/uploads/logo-bhamada-sm.png') }}">

  <!-- DataTables CDN -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet"
    href="{{ asset('stisla/node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/prismjs/themes/prism.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/chocolat/dist/css/chocolat.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/selectric/public/selectric.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/summernote/dist/summernote-bs4.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('stisla/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/assets/css/components.css') }}">

  <!-- Custom -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
    integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>

  @include('sweetalert::alert')

  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li>
              <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                <i class="fas fa-bars"></i>
              </a>
            </li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              @if (auth()->user()->foto != null)
                <img alt="{{ auth()->user()->nama }}" src="{{ asset('storage/uploads/' . auth()->user()->foto) }}"
                  class="rounded-circle mr-1"
                  style="object-fit: cover; object-position: center; width: 30px; height: 30px;">
              @else
                <img alt="Bhamada" src="{{ asset('storage/uploads/logo-bhamada-sm.png') }}"
                  class="rounded-circle mr-1"
                  style="object-fit: cover; object-position: center; width: 30px; height: 30px;">
              @endif
              <div class="d-sm-none d-lg-inline-block">{{ auth()->user()->nama }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="{{ url('profile') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile
              </a>
              <div class="dropdown-divider"></div>
              <a href="{{ route('logout') }}" id="logout" class="dropdown-item has-icon text-danger"
                data-confirm="Logout?|Apakah anda yakin keluar dari <strong>SIMLAB</strong>?"
                data-confirm-yes="modalLogout()">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
              {{-- <form id="logout" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
              </form> --}}
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ url('/') }}">Simlab</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}">Sl</a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li
              class="{{ request()->is('/') || request()->is('dev') || request()->is('admin') || request()->is('peminjam') || request()->is('peminjam/pinjam') || request()->is('laboran') || request()->is('detail-pinjaman*') || request()->is('kalab') || request()->is('web') ? 'active' : '' }}">
              <a class="nav-link" href="{{ url('/') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
              </a>
            </li>

            @if (auth()->user()->isDev())
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
            @endif

            @if (auth()->user()->isAdmin())
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
              {{-- <li class="menu-header">Pengguna</li>
              <li class="{{ request()->is('admin/peminjam*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/peminjam') }}">
                  <i class="fas fa-cog"></i>
                  <span>Data Peminjam</span>
                </a>
              </li> --}}
              <li class="menu-header">Barang & Bahan</li>
              <li class="{{ request()->is('admin/barang*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/barang') }}">
                  <i class="fas fa-cog"></i>
                  <span>Data Barang</span>
                </a>
              </li>
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
            @endif

            <!-- Kalab -->

            @if (auth()->user()->isKalab())
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
              <li class="{{ request()->is('kalab/absen*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('kalab/absen') }}">
                  <i class="fas fa-cog"></i>
                  <span>Data Kunjungan</span>
                </a>
              </li>
            @endif

            <!-- Laboran -->

            @if (auth()->user()->isLaboran())
              {{-- <li class="menu-header">Bahan</li>
              <li class="{{ request()->is('laboran/bahan*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('laboran/bahan') }}">
                  <i class="fas fa-cog"></i>
                  <span>Daftar Bahan</span>
                </a>
              </li> --}}
              @if (auth()->user()->ruangs->first()->tempat_id == '2')
                <li class="menu-header">Mandiri</li>
                <li class="{{ request()->is('laboran/peminjaman*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ url('laboran/peminjaman') }}">
                    <i class="fas fa-cog"></i>
                    <span>Peminjaman</span>
                  </a>
                </li>
                <li class="{{ request()->is('laboran/pengembalian*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ url('laboran/pengembalian') }}">
                    <i class="fas fa-cog"></i>
                    <span>Pengembalian</span>
                  </a>
                </li>
                <li class="{{ request()->is('laboran/riwayat*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ url('laboran/riwayat') }}">
                    <i class="fas fa-cog"></i>
                    <span>Riwayat</span>
                  </a>
                </li>
                <li class="menu-header">Estafet</li>
                <li class="{{ request()->is('laboran/kelompok/peminjaman*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ url('laboran/kelompok/peminjaman') }}">
                    <i class="fas fa-cog"></i>
                    <span>Peminjaman</span>
                  </a>
                </li>
                <li class="{{ request()->is('laboran/kelompok/pengembalian*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ url('laboran/kelompok/pengembalian') }}">
                    <i class="fas fa-cog"></i>
                    <span>Pengembalian</span>
                  </a>
                </li>
                <li class="{{ request()->is('laboran/kelompok/riwayat*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ url('laboran/kelompok/riwayat') }}">
                    <i class="fas fa-cog"></i>
                    <span>Riwayat</span>
                  </a>
                </li>
              @else
                <li class="menu-header">Peminjaman</li>
                <li class="{{ request()->is('laboran/peminjaman-new*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ url('laboran/peminjaman-new') }}">
                    <i class="fas fa-cog"></i>
                    <span>Peminjaman</span>
                  </a>
                </li>
                <li class="{{ request()->is('laboran/pengembalian-new*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ url('laboran/pengembalian-new') }}">
                    <i class="fas fa-cog"></i>
                    <span>Pengembalian</span>
                  </a>
                </li>
                <li class="{{ request()->is('laboran/riwayat-new*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ url('laboran/riwayat-new') }}">
                    <i class="fas fa-cog"></i>
                    <span>Riwayat</span>
                  </a>
                </li>
              @endif
              <li class="menu-header">Lainnya</li>
              <li class="{{ request()->is('laboran/rusak*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('laboran/rusak') }}">
                  <i class="fas fa-cog"></i>
                  <span>Barang Rusak</span>
                </a>
              </li>
            @endif

            <!-- Peminjam -->

            @if (auth()->user()->isPeminjam())
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
            @endif

            @if (auth()->user()->isWeb())
              <li class="menu-header">Berita</li>
              <li class="{{ request()->is('web/berita*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('web/berita') }}">
                  <i class="fas fa-list-alt"></i>
                  <span>Berita</span>
                </a>
              </li>
            @endif
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2022 <div class="bullet"></div> Design By <a href="https://bhamada.ac.id/">IT Bhamada</a>
        </div>
        <div class="footer-right">
          2.3.0
        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="{{ asset('stisla/assets/js/stisla.js') }}"></script>

  <!-- JS Libraies -->
  <script src="{{ asset('stisla/node_modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/prismjs/prism.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/sweetalert/dist/sweetalert.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/jquery-ui-dist/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/select2/dist/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/selectric/public/jquery.selectric.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/jquery_upload_preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/summernote/dist/summernote-bs4.js') }}"></script>

  <!-- Template JS File -->
  <script src="{{ asset('stisla/assets/js/scripts.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/custom.js') }}"></script>

  <!-- Page Specific JS File -->
  <script src="{{ asset('stisla/assets/js/page/modules-datatables.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/page/bootstrap-modal.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/page/modules-sweetalert.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/page/features-posts.js') }}"></script>

  {{-- <script src="{{ asset('stisla/assets/js/page/features-post-create.js') }}"></script> --}}
  {{-- <script src="{{ asset('stisla/assets/js/page/modules-chartjs.js') }}"></script> --}}

  <script>
    function modalLogout() {
      var link = $("#logout").attr("href");
      $(location).attr("href", link);
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @yield('chart')
</body>

</html>
