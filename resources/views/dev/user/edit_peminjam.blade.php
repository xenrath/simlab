@extends('layouts.app')

@section('title', 'Ubah Peminjam')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/user') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>User</h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <div class="alert-title">Error</div>
                </div>
                @foreach (session('error') as $error)
                    <p>
                        <span class="bullet"></span>&nbsp;{{ $error }}
                    </p>
                @endforeach
            </div>
        @endif
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Ubah Peminjam</h4>
                </div>
                <form action="{{ url('dev/user/' . $user->id) }}" method="POST" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <input type="hidden" name="role" id="role" class="form-control" value="peminjam">
                        <div class="form-group mb-3">
                            <label for="kode">NIM</label>
                            <input type="text" name="kode" id="kode" class="form-control"
                                value="{{ $user->kode }}" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="username">
                                Username
                                <small>(digunakan untuk login)</small>
                            </label>
                            <input type="text" name="username" id="username" class="form-control"
                                value="{{ old('username', $user->username) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama Mahasiswa</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama', $user->nama) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="subprodi_id">Prodi</label>
                            <select class="custom-select custom-select-sm" name="subprodi_id" id="subprodi_id">
                                <option value="">- Pilih -</option>
                                @foreach ($subprodis as $subprodi)
                                    <option value="{{ $subprodi->id }}"
                                        {{ old('subprodi_id', $user->subprodi_id) == $subprodi->id ? 'selected' : '' }}>
                                        {{ $subprodi->jenjang }} {{ $subprodi->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tingkat">Tingkat</label>
                            <select class="custom-select custom-select-sm" name="tingkat" id="tingkat">
                                <option value="">- Pilih -</option>
                                <option value="1" {{ old('tingkat', $user->tingkat) == '1' ? 'selected' : '' }}>1
                                </option>
                                <option value="2" {{ old('tingkat', $user->tingkat) == '2' ? 'selected' : '' }}>2
                                </option>
                                <option value="3" {{ old('tingkat', $user->tingkat) == '3' ? 'selected' : '' }}>3
                                </option>
                                <option value="4" {{ old('tingkat', $user->tingkat) == '4' ? 'selected' : '' }}>4
                                </option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="telp">
                                No. Telepon
                                <small>(opsional)</small>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">+62</div>
                                </div>
                                <input type="text" class="form-control" name="telp" id="telp"
                                    value="{{ old('telp', $user->telp) }}">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat">
                                Alamat
                                <small>(opsional)</small>
                            </label>
                            <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="10" style="height: 80px">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="foto">
                                Foto
                                <small>(kosongkan saja jika tidak ingin
                                    diubah)</small>
                            </label>
                            <input type="file" name="foto" id="foto" class="form-control"
                                value="{{ old('foto') }}" accept="image/*">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="chocolat-parent">
                                    @if ($user->foto != null)
                                        <a href="{{ asset('storage/uploads/' . $user->foto) }}" class="chocolat-image"
                                            title="{{ $user->nama }}">
                                            <div data-crop-image="h-100">
                                                <img alt="image" src="{{ asset('storage/uploads/' . $user->foto) }}"
                                                    class="img-fluid img-thumbnail w-100">
                                            </div>
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="chocolat-image"
                                            title="{{ $user->nama }}">
                                            <div data-crop-image="h-100">
                                                <img alt="image"
                                                    src="{{ asset('storage/uploads/logo-bhamada1.png') }}"
                                                    class="img-fluid img-thumbnail">
                                            </div>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke justify-content-between d-flex">
                        <button type="button" class="btn btn-warning" data-toggle="modal"
                            data-target="#modal-reset-password">
                            Reset Password
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-reset-password">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0">
                    <p>Password akan diubah menjadi <strong>{{ $user->username }}</strong>.<br>Yakin reset password?</p>
                </div>
                <div class="modal-footer bg-whitesmoke">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="{{ url('dev/user/reset_password/' . $user->id) }}" class="btn btn-warning">Reset</a>
                </div>
            </div>
        </div>
    </div>
@endsection
