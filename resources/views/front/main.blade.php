<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Laboratorium Terpadu | Bhamada</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('medilab/assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('medilab/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('medilab/assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('medilab/assets/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
  <link href="{{ asset('medilab/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('medilab/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('medilab/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('medilab/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('medilab/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('medilab/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('medilab/assets/css/style10.css') }}" rel="stylesheet" type="text/css">

  <!-- =======================================================
  * Template Name: Medilab - v4.10.0
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Top Bar ======= -->
  <div id="topbar" class="d-flex align-items-center fixed-top">
    <div class="container d-flex justify-content-between">
      <div class="contact-info d-none d-lg-flex align-items-center">
        <i class="bi bi-clock"></i> Senin - Kamis : 08.00 - 16.00 | Jum'at : 08.00 - 11.30</a>
      </div>
      <div class="contact-info d-lg-none d-flex align-items-center">
        <i class="bi bi-clock"></i>
        Senin - Kamis : 08.00 - 16.00 <br>
        Jum'at : 08.00 - 11.30</a>
      </div>
    </div>
  </div>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">
      <a href="{{ url('/') }}" class="logo me-auto">
        <img src="{{ asset('medilab/assets/img/logo-bhamada-sm.png') }}" alt="Logo Bhamada"
          class="logo-image d-none d-md-inline">
        <div class="logo-title">
          <p>
            UPT Laboratorium Terpadu <br>
            <span class="d-none d-md-inline">Universitas Bhamada</span>
          </p>
        </div>
      </a>
      <nav id="navbar" class="navbar order-last order-lg-0">
        @if (request()->is('/'))
          <ul>
            <li>
              <a class="nav-link scrollto active" href="#beranda">Beranda</a>
            </li>
            <li>
              <a class="nav-link scrollto" href="#tentang">Tentang</a>
            </li>
            <li>
              <a class="nav-link scrollto" href="#departments">Profil</a>
            </li>
            <li>
              <a class="nav-link scrollto" href="#berita">Berita</a>
            </li>
            <li><a class="nav-link scrollto" href="#kontak">Kontak</a></li>
          </ul>
        @else
          <ul>
            <li>
              <a class="nav-link scrollto" href="{{ url('/#beranda') }}">Beranda</a>
            </li>
            <li>
              <a class="nav-link scrollto" href="{{ url('/#tentang') }}">Tentang</a>
            </li>
            <li>
              <a class="nav-link scrollto" href="{{ url('/#departments') }}">Profil</a>
            </li>
            <li>
              <a class="nav-link scrollto {{ request()->is('berita*') ? 'active' : '' }}"
                href="{{ url('/#berita') }}">Berita</a>
            </li>
            <li><a class="nav-link scrollto" href="{{ url('/#kontak') }}">Kontak</a></li>
          </ul>
        @endif
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
      <!-- .navbar -->
      <a href="{{ route('login') }}" class="header-btn scrollto"><span
          class="d-none d-md-inline">Masuk</span>
        SIMLAB</a>
    </div>
  </header><!-- End Header -->

  @if (request()->is('/'))
    <!-- ======= Hero Section ======= -->
    <section id="beranda" class="d-flex align-items-center">
      <div class="container">
        <div class="beranda-text">
          <h1>UPT Laboratorium Terpadu</h1>
          <h2>Universitas Bhamada Slawi</h2>
          <a href="#tentang" class="btn-get-started scrollto">Mulai</a>
        </div>
      </div>
    </section>
    <!-- End Hero -->
  @endif

  <main id="main">
    @yield('content')
  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-6 footer-contact">
            <h3 class="mb-3">Universitas Bhamada Slawi</h3>
            <p>
            <table>
              <tr>
                <th class="name">Email</th>
                <td class="colon">:</td>
                <td>info@bhamada.ac.id</td>
              </tr>
              <tr>
                <th class="name">Telepon</th>
                <td class="colon">:</td>
                <td>(0283) 6197570</td>
              </tr>
              <tr>
                <th class="name">Alamat</th>
                <td class="colon">:</td>
                <td>Jl. Cut Nyak Dhien No.16, Desa Kalisapu, Kecamatan Slawi, Kabupaten Tegal 52416</td>
              </tr>
            </table>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Link Lab. Terpadu</h4>
            @if (request()->is('/'))
              <ul>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#beranda" class="scrollto">Beranda</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#tentang" class="scrollto">Tentang</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#profil" class="scrollto">Profil</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#berita" class="scrollto">Berita</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="#kontak" class="scrollto">Kontak</a>
                </li>
              </ul>
            @else
              <ul>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="{{ url('/#beranda') }}" class="scrollto">Beranda</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="{{ url('/#tentang') }}" class="scrollto">Tentang</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="{{ url('/#profil') }}" class="scrollto">Profil</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="{{ url('/#berita') }}" class="scrollto">Berita</a>
                </li>
                <li>
                  <i class="bx bx-chevron-right"></i>
                  <a href="{{ url('/#kontak') }}" class="scrollto">Kontak</a>
                </li>
              </ul>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="container d-md-flex py-4">
      <div class="me-md-auto text-center text-md-start">
        <div class="copyright">
          &copy; Copyright <strong><span>UPT Laboratorium Terpadu</span></strong>. Designed by <a
            href="https://uptsit.bhamada.com">UPT Sistem Informasi dan Teknologi</a>
        </div>
      </div>
    </div>
  </footer>
  <!-- End Footer -->
  <div id="preloader"></div>
  <a href="" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('medilab/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('medilab/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('medilab/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('medilab/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('medilab/assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('medilab/assets/js/main.js') }}"></script>

</body>

</html>
