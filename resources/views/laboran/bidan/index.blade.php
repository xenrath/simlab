@extends('layouts.app')

@section('title', 'Dashboard')

@section('style')
    <style>
        .card-hover {
            transition: all 0.3s ease-in-out;
        }

        .card-hover:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            transform: scale(1.03);
            /* background-color: #f0f4ff; */
            /* Opsional */
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="section-body">
            <div class="hero bg-white rounded-0 p-4 mb-3">
                <div class="hero-inner">
                    <div class="mb-3">
                        <h5 class="text-primary font-weight-normal d-none d-md-block">SISTEM INFORMASI MANAGEMEN
                            LABORATORIUM | UNIVERSITAS BHAMADA SLAWI</h5>
                        <h5 class="text-primary font-weight-normal d-block d-md-none">SIMLAB | BHAMADA</h5>
                    </div>
                    <div class="mb-3">Untuk keperluan Anda, lengkapi data diri Anda terlebih dahulu</div>
                    <div class="mb-4">
                        <h5 class="font-weight-normal">{{ auth()->user()->nama }}</h5>
                        <h6 class="font-weight-light">{{ auth()->user()->telp ?? '-' }}</h6>
                    </div>
                    <button type="button"
                        class="btn {{ session('profile') ? 'btn-outline-danger' : 'btn-outline-primary' }} mr-2 mb-2 rounded-0"
                        data-toggle="modal" data-target="#modal-profile">
                        <i class="fas fa-user-edit mr-1"></i>
                        Perbarui Profile
                    </button>
                    <button type="button"
                        class="btn {{ session('password') ? 'btn-outline-danger' : 'btn-outline-primary' }} mr-2 mb-2 rounded-0"
                        data-toggle="modal" data-target="#modal-password">
                        <i class="fas fa-key mr-1"></i>
                        Perbarui Password
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <a href="{{ url('laboran/bidan/peminjaman') }}">
                        <div class="card card-hover card-statistic-1 rounded-0 mb-3 border-0 shadow-sm">
                            <div class="card-icon bg-primary rounded-0">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Peminjaman Menunggu</h4>
                                </div>
                                <div class="card-body">
                                    {{ $menunggu }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ url('laboran/bidan/pengembalian') }}">
                        <div class="card card-hover card-statistic-1 rounded-0 mb-3 border-0 shadow-sm">
                            <div class="card-icon bg-warning rounded-0">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Dalam Peminjaman</h4>
                                </div>
                                <div class="card-body">
                                    {{ $proses }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ url('laboran/bidan/tagihan') }}">
                        <div class="card card-hover card-statistic-1 rounded-0 mb-3 border-0 shadow-sm">
                            <div class="card-icon bg-danger rounded-0">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Tagihan</h4>
                                </div>
                                <div class="card-body">
                                    {{ $proses }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-profile">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">Perbarui Profile</h5>
                </div>
                <form action="{{ url('laboran/profile') }}" method="POST" id="form-profile" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control rounded-0 @error('nama') is-invalid @enderror"
                                name="nama" id="nama" value="{{ old('nama', auth()->user()->nama) }}">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="telp">
                                Nomor WhatsApp
                                <small class="text-muted">(contoh: 08xxxxxxxxxx)</small>
                            </label>
                            <input type="tel" class="form-control rounded-0 @error('telp') is-invalid @enderror"
                                name="telp" id="telp" value="{{ old('telp', auth()->user()->telp) }}">
                            @error('telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary rounded-0" id="btn-profile" onclick="form_profile()">
                            <div id="btn-profile-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-profile-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-password">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">Perbarui Password</h5>
                </div>
                <form action="{{ url('laboran/password') }}" method="POST" id="form-password" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="password">Password Baru</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password"
                                    class="form-control rounded-0 @error('password') is-invalid @enderror"
                                    onkeypress="return event.charCode != 32" value="{{ old('password') }}">
                                <div class="input-group-append" style="cursor: pointer;" onclick="show_password()">
                                    <div class="input-group-text rounded-0">
                                        <i id="password-icon" class="fas fa-eye"></i>
                                    </div>
                                </div>
                            </div>
                            @error('password')
                                <div class="text-danger">
                                    <small>{{ $message }}</small>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="password-confirmation">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" id="password-confirmation" name="password_confirmation"
                                    class="form-control rounded-0 @error('password_confirmation') is-invalid @enderror"
                                    onkeypress="return event.charCode != 32" value="{{ old('password_confirmation') }}">
                                <div class="input-group-append" style="cursor: pointer;"
                                    onclick="show_password_confirmation()">
                                    <div class="input-group-text rounded-0">
                                        <i id="password-confirmation-icon" class="fas fa-eye"></i>
                                    </div>
                                </div>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger">
                                    <small>{{ $message }}</small>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary rounded-0" id="btn-password"
                            onclick="form_password()">
                            <div id="btn-password-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-password-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function form_profile() {
            $('#btn-profile').prop('disabled', true);
            $('#btn-profile-text').hide();
            $('#btn-profile-load').show();
            $('#form-profile').submit();
        }
        // 
        function form_password() {
            $('#btn-password').prop('disabled', true);
            $('#btn-password-text').hide();
            $('#btn-password-load').show();
            $('#form-password').submit();
        }
        // 
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
        function show_password_confirmation() {
            var class_icon = $('#password-confirmation-icon').attr('class');
            if (class_icon === 'fas fa-eye') {
                $('#password-confirmation-icon').attr('class', 'fas fa-eye-slash');
                $('#password-confirmation').attr('type', 'text');
            } else if (class_icon === 'fas fa-eye-slash') {
                $('#password-confirmation-icon').attr('class', 'fas fa-eye');
                $('#password-confirmation').attr('type', 'password');
            }
        }
    </script>
@endsection
