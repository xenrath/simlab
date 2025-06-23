@extends('layouts.app')

@section('title', 'Edit Laboran')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/laboran') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Laboran</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Edit Laboran</h4>
                </div>
                <form action="{{ url('admin/laboran/' . $user->id) }}" method="POST" autocomplete="off"
                    enctype="multipart/form-data" id="form-submit">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="username">
                                Username
                                <small class="text-muted">(digunakan untuk login)</small>
                            </label>
                            <input type="text" name="username" id="username"
                                class="form-control rounded-0 @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="nama">Nama Laboran</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control rounded-0 @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $user->nama) }}">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="prodi_id">Prodi</label>
                            <select class="custom-select custom-select-sm rounded-0 @error('nama') is-invalid @enderror"
                                id="prodi_id" name="prodi_id">
                                <option value="">Pilih Prodi</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id', $user->prodi_id) == $prodi->id ? 'selected' : '' }}>
                                        {{ ucfirst($prodi->singkatan) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="telp">
                                No. Telepon
                                <small class="text-muted">(opsional)</small>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text rounded-0">+62</div>
                                </div>
                                <input type="tel" class="form-control rounded-0 @error('telp') is-invalid @enderror"
                                    name="telp" id="telp" value="{{ old('telp', $user->telp) }}"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                @error('telp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="alamat">
                                Alamat
                                <small class="text-muted">(opsional)</small>
                            </label>
                            <textarea class="form-control rounded-0 @error('kode') is-invalid @enderror" name="alamat" id="alamat"
                                cols="30" rows="10" style="height: 80px">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="foto">
                                Foto
                                <small class="text-muted">(kosongkan saja jika tidak ingin
                                    diubah | opsional)</small>
                            </label>
                            <input type="file" name="foto" id="foto"
                                class="form-control rounded-0 @error('kode') is-invalid @enderror"
                                value="{{ old('foto', $user->foto) }}" aria-describedby="foto-help" accept="image/*">
                            @error('telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @if ($user->foto)
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="chocolat-parent">
                                        <a href="{{ asset('storage/uploads/' . $user->foto) }}" class="chocolat-image"
                                            title="{{ $user->nama }}">
                                            <div data-crop-image="h-100">
                                                <img alt="image" src="{{ asset('storage/uploads/' . $user->foto) }}"
                                                    class="img-fluid w-100 border rounded-0">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-whitesmoke justify-content-between d-flex">
                        <button type="button" class="btn btn-warning rounded-0" data-toggle="modal"
                            data-target="#modal-password">Reset
                            Password
                        </button>
                        <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                            <div id="btn-submit-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-submit-text">Edit Mahasiswa</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-password">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header pb-3 border-bottom">
                    <h5 class="modal-title">Reset Password</h5>
                </div>
                <div class="modal-body">
                    Password akan diubah menjadi <strong>{{ $user->username }}</strong>
                    <br>
                    Yakin reset password?
                </div>
                <div class="modal-footer bg-whitesmoke justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                    <form action="{{ url('admin/laboran/reset_password/' . $user->id) }}" method="GET"
                        id="form-reset">
                        <button type="button" class="btn btn-warning rounded-0" id="btn-reset" onclick="form_reset()">
                            <div id="btn-reset-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-reset-text">Reset</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function form_submit() {
            $('#btn-submit').prop('disabled', true);
            $('#btn-submit-text').hide();
            $('#btn-submit-load').show();
            $('#form-submit').submit();
        }
    </script>
    <script>
        function form_reset() {
            $('#btn-reset').prop('disabled', true);
            $('#btn-reset-text').hide();
            $('#btn-reset-load').show();
            $('#form-reset').submit();
        }
    </script>
@endsection
