<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no, user-scalable=no"
        name="viewport">
    <title>Login &mdash; Simlab</title>

    <link rel="icon" href="{{ asset('storage/uploads/logo-bhamada1.png') }}" sizes="16x16">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('stisla/node_modules/bootstrap-social/bootstrap-social.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('stisla/assets/css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('stisla/assets/css/components.css') }}"> --}}
</head>

<body>

    @include('sweetalert::alert')

    <div id="app">
        <section class="section">
            <div class="d-flex flex-wrap align-items-stretch">
                <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
                    <div class="p-4 m-3">
                        <img src="{{ asset('storage/uploads/asset/logo-bhamada-80.webp') }}" alt="Logo Bhamada"
                            style="width: 80px; height: 81px;" class="shadow-light rounded-circle mb-5 mt-2">
                        <h4 class="text-dark font-weight-bold">SIMLAB</h4>
                        <h4 class="text-dark font-weight-normal">Universitas Bhamada Slawi</h4>
                        <p class="text-muted">Sistem Informasi Managemen Laboratorium</p>
                        <form method="POST" action="{{ route('login') }}" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input id="username" type="text"
                                    class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                    name="username" value="{{ old('username') }}" tabindex="1" autofocus>
                                @if ($errors->has('username'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('username') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="d-block">
                                    <label for="password" class="control-label">Password</label>
                                </div>
                                <input id="password" type="password"
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    name="password" tabindex="2">
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right w-100"
                                    tabindex="3">
                                    M A S U K
                                </button>
                            </div>
                        </form>
                        <a href="{{ url('absen') }}" class="btn btn-info btn-lg float-right " tabindex="4">
                            ISI BUKU KUNJUNGAN&nbsp;&nbsp;&nbsp;<i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom"
                    data-background="{{ asset('storage/uploads/asset/login-new.webp') }}">
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
</body>

</html>
