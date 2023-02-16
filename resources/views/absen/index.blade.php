<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

  <title>Absensi &mdash; Lab. Terpadu</title>

  <!-- Icon -->
  <link rel="icon" href="{{ asset('storage/uploads/logo-bhamada.png') }}">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/bootstrap-social/bootstrap-social.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('stisla/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/assets/css/components.css') }}">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="d-flex flex-wrap align-items-stretch">
        <div class="col-lg-6 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
          <div class="p-3 m-3">
            <table class="table">
              <tr>
                <td>
                  <img src="{{ asset('storage/uploads/logo-bhamada1.png') }}" alt="logo" width="80"
                    class="shadow-light rounded-circle mb-4 mt-2">
                </td>
                <td>
                  <h4 class="text-dark font-weight-normal">BUKU KUNJUNGAN</h4>
                  <h4 class="text-dark font-weight-bold">LABORATORIUM TERPADU</h4>
                </td>
              </tr>
            </table>
            <div class="card card-danger shadow">
              <div class="card-header">
                <h4>Perhatikan</h4>
              </div>
              <div class="card-body">
                <p>
                  <span class="bullet"></span> Untuk mahasiswa masukan NIM saja
                  <br>
                  <span class="bullet"></span> Untuk tamu masukan Nama dan Institusi
                </p>
              </div>
            </div>
            <form method="POST" action="{{ url('absen') }}" autocomplete="off">
              @csrf
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="username">NIM / NAMA</label>
                    <input id="username" type="text" class="form-control form-control-lg" name="username" tabindex="1" value="{{ old('username') }}"
                      autofocus>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="institusi">INSTITUSI</label>
                    <input id="institusi" type="text" class="form-control form-control-lg" name="institusi"
                      tabindex="2">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right w-100" tabindex="3">
                  S U B M I T
                </button>
              </div>
            </form>
            <a href="{{ route('login') }}" class="btn btn-info btn-lg float-right " tabindex="4">
              MASUK SIMLAB&nbsp;&nbsp;&nbsp;<i class="fas fa-chevron-right"></i>
            </a>
          </div>
        </div>
        <div
          class="col-lg-6 col-12 order-lg-2 p-5 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom"
          data-background="{{ asset('stisla/assets/img/unsplash/login-new.jpg') }}">
          @if (session('status'))
          <div class="card index-3" id="card-message">
            @if (session('error'))
            <div class="card-header">
              <h4>Error</h4>
              <div class="card-header-action">
                <a data-dismiss="#card-message" class="btn btn-icon btn-danger" href="">
                  <i class="fas fa-times"></i>
                </a>
              </div>
            </div>
            <div class="card-body">
              <h5 class="text-center">{{ session('error') }}</h5>
            </div>
            @else
            <div class="card-header">
              <h4>Selamat Datang di Laboratorium Terpadu</h4>
              <div class="card-header-action">
                <a data-dismiss="#card-message" class="btn btn-icon btn-danger" href="#">
                  <i class="fas fa-times"></i>
                </a>
              </div>
            </div>
            <div class="card-body">
              @if (session('role') == 'mahasiswa')
              @if (session('success'))
              @php
              $absen = session('success');
              @endphp
              <table class="w-100">
                <tr height="50">
                  <th width="100">Nama</th>
                  <td width="40">:</td>
                  <td>{{ $absen->user->nama }}</td>
                </tr>
                <tr height="50">
                  <th width="100">NIM</th>
                  <td width="40">:</td>
                  <td>{{ $absen->user->kode }}</td>
                </tr>
                <tr height="50">
                  <th width="100">Prodi</th>
                  <td width="40">:</td>
                  <td>{{ $absen->user->prodi->nama }}</td>
                </tr>
                <tr height="50">
                  <th width="100">Semester</th>
                  <td width="40">:</td>
                  <td>{{ $absen->user->semester }}</td>
                </tr>
              </table>
              @endif
              @endif
              @if (session('role') == 'tamu')
              @if (session('success'))
              @php
              $absen = session('success');
              @endphp
              <table class="w-100">
                <tr height="50">
                  <th width="100">Nama</th>
                  <td width="40">:</td>
                  <td>{{ $absen->username }}</td>
                </tr>
                <tr height="50">
                  <th width="100">Institusi</th>
                  <td width="40">:</td>
                  <td>{{ $absen->institusi }}</td>
                </tr>
              </table>
              @endif
              @endif
            </div>
            @endif
          </div>
          @endif
          <div class="absolute-bottom-left index-2">
            <div class="text-light p-5 pb-2">
              Copyright &copy; <a href="">IT Bhamada.</a> Made with 💙 by Stisla
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script>
    $username = document.getElementById('username');
    $instansi = document.getElementById('instansi');
  </script>
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

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="{{ asset('stisla/assets/js/scripts.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/custom.js') }}"></script>

  <!-- Page Specific JS File -->
</body>
</html>