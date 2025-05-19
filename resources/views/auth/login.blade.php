<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no, user-scalable=no"
        name="viewport">
    <meta name="google" value="notranslate">
    <title>LOGIN SIMLAB</title>
    <link rel="icon" href="{{ asset('storage/uploads/logo-bhamada1.png') }}" sizes="16x16">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('stisla/node_modules/bootstrap-social/bootstrap-social.css') }}">
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
                        <img src="{{ asset('storage/uploads/asset/logo-bhamada-80.webp') }}" alt="Logo Bhamada"
                            style="width: 80px; height: 81px;" class="shadow-light rounded-circle mb-5 mt-2">
                        <h4 class="text-dark font-weight-bold">SIMLAB</h4>
                        <h4 class="text-dark font-weight-normal">Universitas Bhamada Slawi</h4>
                        <p class="text-muted">Sistem Informasi Managemen Laboratorium</p>
                        <form method="POST" action="{{ route('login') }}" autocomplete="off" id="form-submit">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="username">Username</label>
                                <input id="username" type="text"
                                    class="form-control rounded-0 {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                    name="username" value="{{ old('username') }}" autofocus tabindex="1">
                                @if ($errors->has('username'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('username') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group mb-2">
                                <div class="d-block">
                                    <label for="password" class="control-label">Password</label>
                                </div>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                        class="form-control rounded-0 {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                        onkeypress="return event.charCode != 32" tabindex="2">
                                    <div class="input-group-append" style="cursor: pointer;" onclick="show_password()">
                                        <div class="input-group-text rounded-0">
                                            <i id="password-icon" class="fas fa-eye"></i>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('password'))
                                    <div class="text-danger">
                                        <small>{{ $errors->first('password') }}</small>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 mb-2">
                                <button type="submit" class="btn btn-primary btn-lg rounded-0 w-100" tabindex="3"
                                    id="btn-submit" onclick="form_submit()">
                                    <i id="btn-submit-load" class="fa fa-spinner fa-spin" style="display: none;"></i>
                                    <span id="btn-submit-text">MASUK</span>
                                </button>
                            </div>
                        </form>
                        <div class="mt-4 mb-2">
                            <a href="{{ url('absen') }}" class="btn btn-info float-right rounded-0">
                                BUKU KUNJUNGAN
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom"
                    data-background="{{ asset('storage/uploads/asset/login-new.webp') }}">
                    <div class="absolute-bottom-left index-2">
                        <div class="text-light p-5 pb-2">
                            <div class="mb-5 pb-3">
                                <h1 class="mb-2 display-4 font-weight-bold">
                                    SIMLAB
                                </h1>
                                <h5 class="font-weight-normal text-muted-transparent">
                                    Sistem Informasi Managemen Laboratorium
                                </h5>
                            </div>
                            Copyright &copy; <strong>Bhamada</strong> Made with 💙 by <a
                                href="https://it.bhamada.ac.id/" target="_blank">IT Bhamada.</a>
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

    <script>
        function show_password() {
            var class_icon = $('#password-icon').attr('class');
            if (class_icon === 'fas fa-eye') {
                $('#password-icon').attr('class', 'fas fa-eye-slash');
                $('#password').attr('type', 'text');
            } else if (class_icon === 'fas fa-eye-slash') {
                $('#password-icon').attr('class', 'fas fa-eye');
                $('#password').attr('type', 'password');
            }
        }
        // 
        function form_submit() {
            $('#btn-submit').prop('disabled', true);
            $('#btn-submit-text').hide();
            $('#btn-submit-load').show();
            $('#form-submit').submit();
        }
    </script>
</body>

</html>
