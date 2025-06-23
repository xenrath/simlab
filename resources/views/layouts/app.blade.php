<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Sistem Informasi Management Laboratoritum - Universitas Bhamada Slawi">
    <meta name="keywords"
        content="Simlab, Bhamada, Laboratorium, Peminjaman, Simlab Bhamada, Simlab Universitas Bhamada, Universitas Bhamada, Peminjaman Alat Laboratorium, Sistem Peminjaman Alat Lab">
    <meta name="author" content="IT Bhamada">
    <meta name="google" value="notranslate">
    {{-- {!! SEO::generate(true) !!} --}}
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <title>@yield('title')</title>

    <!-- Icon -->
    <link rel="icon" href="{{ asset('storage/uploads/logo-bhamada-sm.png') }}">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- CSS Libraries -->
    {{-- <link rel="stylesheet" href="{{ asset('stisla/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('stisla/node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('stisla/node_modules/prismjs/themes/prism.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('stisla/node_modules/chocolat/dist/css/chocolat.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('stisla/node_modules/select2/dist/css/select2.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('stisla/node_modules/selectric/public/selectric.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('stisla/node_modules/summernote/dist/summernote-bs4.css') }}"> --}}

    <!-- CSS Libraries -->
    @yield('style')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('stisla/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('stisla/assets/css/components.css') }}">
</head>

<body>

    @include('sweetalert::alert')

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
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
                        <a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <div class="d-sm-none d-lg-inline-block">{{ auth()->user()->nama }}</div>
                            <div class="d-inline-block d-lg-none">{{ Str::limit(auth()->user()->nama, 30) }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right rounded-0">
                            <a href="#" data-toggle="sidebar" class="dropdown-item">
                                <i class="fas fa-bars mr-2"></i>
                                <span>Menu</span>
                            </a>
                            {{-- <div class="dropdown-divider"></div>
                            <a href="{{ url('profile') }}" class="dropdown-item">
                                <i class="fas fa-user mr-2"></i>
                                <span>Profile</span>
                            </a> --}}
                            {{-- <div class="dropdown-divider"></div>
                            <a href="{{ url('password') }}" class="dropdown-item">
                                <i class="fas fa-key mr-2"></i>
                                <span>Password</span>
                            </a> --}}
                            <div class="dropdown-divider"></div>
                            <a href="#" id="logout" class="dropdown-item text-danger" data-toggle="modal"
                                data-target="#modal-logout">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="{{ url('/') }}">
                            <img alt="Bhamada" src="{{ asset('storage/uploads/logo-bhamada-sm.png') }}"
                                class="rounded-circle mr-1"
                                style="object-fit: cover; object-position: center; width: 40px; height: 40px;">
                        </a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="{{ url('/') }}">
                            <img alt="Bhamada" src="{{ asset('storage/uploads/logo-bhamada-sm.png') }}"
                                class="rounded-circle mr-1"
                                style="object-fit: cover; object-position: center; width: 40px; height: 40px;">
                        </a>
                    </div>
                    <ul class="sidebar-menu mt-3">
                        <li class="menu-header">Dashboard</li>
                        <li
                            class="{{ request()->is('/') ||
                            request()->is('dev') ||
                            request()->is('admin') ||
                            request()->is('peminjam/labterpadu') ||
                            request()->is('peminjam/farmasi') ||
                            request()->is('peminjam/feb') ||
                            request()->is('laboran') ||
                            request()->is('kalab') ||
                            request()->is('web')
                                ? 'active'
                                : '' }}">
                            <a class="nav-link rounded-0" href="{{ url('/') }}">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        @if (auth()->user()->isDev())
                            @include('layouts.menu.dev')
                        @elseif (auth()->user()->isAdmin())
                            @include('layouts.menu.admin')
                        @elseif (auth()->user()->isKalab())
                            @include('layouts.menu.kalab')
                        @elseif (auth()->user()->isLaboran())
                            @include('layouts.menu.laboran')
                        @elseif (auth()->user()->isPeminjam())
                            @include('layouts.menu.peminjam')
                        @elseif (auth()->user()->isWeb())
                            @include('layouts.menu.web')
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
                    Copyright &copy; 2025 <div class="bullet"></div> Design By <a href="https://it.bhamada.ac.id/"
                        target="_blank">IT Bhamada</a>
                </div>
                <div class="footer-right">4.0</div>
            </footer>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="modal-logout">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">LOGOUT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span>Apakah Anda yakin keluar dari <strong>SIMLAB</strong>?</span>
                </div>
                <div class="modal-footer bg-whitesmoke justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="get" id="form-logout">
                        <button type="button" class="btn btn-danger rounded-0" id="btn-logout"
                            onclick="form_logout()">
                            <div id="btn-logout-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-logout-text">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ asset('stisla/assets/js/stisla.js') }}"></script>

    <!-- JS Library -->
    @yield('script')

    <!-- JS Libraies -->
    {{-- <script src="{{ asset('stisla/node_modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('stisla/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('stisla/node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('stisla/node_modules/prismjs/prism.js') }}"></script>
    <script src="{{ asset('stisla/node_modules/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('stisla/node_modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('stisla/node_modules/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('stisla/node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('stisla/node_modules/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('stisla/node_modules/jquery_upload_preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
    <script src="{{ asset('stisla/node_modules/summernote/dist/summernote-bs4.js') }}"></script> --}}

    <!-- Template JS File -->
    <script src="{{ asset('stisla/assets/js/scripts.js') }}"></script>
    <script src="{{ asset('stisla/assets/js/custom.js') }}"></script>

    <!-- Page Specific JS File -->
    {{-- <script src="{{ asset('stisla/assets/js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('stisla/assets/js/page/bootstrap-modal.js') }}"></script>
    <script src="{{ asset('stisla/assets/js/page/modules-sweetalert.js') }}"></script>
    <script src="{{ asset('stisla/assets/js/page/features-posts.js') }}"></script> --}}

    <script>
        function form_logout() {
            $('#btn-logout').prop('disabled', true);
            $('#btn-logout-text').hide();
            $('#btn-logout-load').show();
            $('#form-logout').submit();
        }
    </script>
</body>

</html>
