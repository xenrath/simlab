<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
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

            <!-- Laboran -->

            @if (auth()->user()->isLaboran())
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

            <!-- Peminjam -->

            @if (auth()->user()->isPeminjam())
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
</body>

</html>
