<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

  <title>Absensi &mdash; Lab. Terpadu</title>

  <!-- Icon -->
  <link rel="icon" href="{{ asset('storage/uploads/logo-bhamada1.png') }}">

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

  @include('sweetalert::alert')

  <div id="app">
    <section class="section">
      <div class="d-flex flex-wrap align-items-stretch">
        <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
          <div class="p-4 m-3">
            <img src="{{ asset('storage/uploads/logo-bhamada1.png') }}" alt="logo" width="80"
              class="shadow-light rounded-circle mb-5 mt-2">
            <h4 class="text-dark font-weight-bold">ABSENSI SIMLAB</h4>
            <h4 class="text-dark font-weight-normal">Universitas Bhamada Slawi</h4>
            <p class="text-muted">Sistem Informasi Managemen Laboratorium</p>
            @if (session('error'))
              <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  <p>
                    @foreach (session('error') as $error)
                      <span class="bullet"></span>&nbsp;{{ $error }}
                      <br>
                    @endforeach
                  </p>
                </div>
              </div>
            @endif
            <form method="POST" action="{{ url('absen') }}" autocomplete="off">
              @csrf
              <div class="form-group">
                <div class="selectgroup w-100">
                  <label class="selectgroup-item w-50">
                    <input type="radio" name="check" id="check" value="1" class="selectgroup-input"
                      onclick="click_radio()" {{ old('check', '1') == '1' ? 'checked' : '' }}>
                    <span class="selectgroup-button">Mahasiswa</span>
                  </label>
                  <label class="selectgroup-item w-50">
                    <input type="radio" name="check" id="check" value="0" class="selectgroup-input"
                      onclick="click_radio()" {{ old('check') == '0' ? 'checked' : '' }}>
                    <span class="selectgroup-button">Lainnya</span>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label for="username" id="label_username">NIM</label>
                <input id="username" type="text" class="form-control form-control" name="username" tabindex="1"
                  value="{{ old('username') }}" autofocus>
              </div>
              <div id="layout_institusi">
                <div class="form-group">
                  <label for="institusi">Institusi</label>
                  <input id="institusi" type="text" class="form-control form-control" name="institusi"
                    tabindex="2">
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
          class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom"
          data-background="{{ asset('stisla/assets/img/unsplash/login-new.jpg') }}">
          <div class="absolute-bottom-left index-2">
            <div class="text-light p-5 pb-2">
              <div class="mb-5 pb-3">
                <h1 class="mb-2 display-4 font-weight-bold">SIMLAB</h1>
                <h5 class="font-weight-normal text-muted-transparent">Sistem Informasi Managemen
                  Laboratorium</h5>
              </div>
              Copyright &copy; <a href="">IT Bhamada.</a> Made with 💙 by Stisla
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script>
    var label_username = document.getElementById('label_username');
    var username = document.getElementById('username');
    var layout_institusi = document.getElementById('layout_institusi');

    var radioButtons = document.querySelectorAll('input[name="check"]');

    var selectedValue = "1";
    for (const radioButton of radioButtons) {
      if (radioButton.checked) {
        selectedValue = radioButton.value;
        break;
      }
    }

    console.log(selectedValue);

    if (selectedValue == '1') {
      label_username.innerHTML = 'NIM';
      layout_institusi.style.display = "none";
    } else if (selectedValue == '0') {
      label_username.innerHTML = 'Nama';
      layout_institusi.style.display = "inline";
    }

    function click_radio() {
      for (const radioButton of radioButtons) {
        if (radioButton.checked) {
          selectedValue = radioButton.value;
          break;
        }
      }
      if (selectedValue == '1') {
        label_username.innerHTML = 'NIM';
        layout_institusi.style.display = "none";
      } else if (selectedValue == '0') {
        label_username.innerHTML = 'Nama';
        layout_institusi.style.display = "inline";
      }
    }
  </script>

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

  <!-- Template JS File -->
  <script src="{{ asset('stisla/assets/js/scripts.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/custom.js') }}"></script>

  <!-- Page Specific JS File -->
</body>

</html>
