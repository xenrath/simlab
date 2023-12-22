@extends('layouts.app')

@section('title', 'Ubah Mahasiswa')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/pengguna/mahasiswa') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Mahasiswa</h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="alert-body">
                    <div class="alert-title">Error!</div>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <ul class="px-3 mb-0">
                        @foreach (session('error') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Ubah Mahasiswa</h4>
                </div>
                <form action="{{ url('admin/pengguna/mahasiswa/' . $user->id) }}" method="POST" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama', $user->nama) }}">
                        </div>
                        <div class="form-group">
                            <label for="kode">NIM</label>
                            <input type="text" name="kode" id="kode" class="form-control"
                                value="{{ old('kode', $user->kode) }}">
                        </div>
                        <div class="form-group">
                            <label for="subprodi_id">Prodi</label>
                            <select class="form-control selectric" id="subprodi_id" name="subprodi_id">
                                <option value="">Pilih Prodi</option>
                                @foreach ($subprodis as $subprodi)
                                    <option value="{{ $subprodi->id }}"
                                        {{ old('subprodi_id', $user->subprodi_id) == $subprodi->id ? 'selected' : '' }}>
                                        {{ $subprodi->jenjang }}
                                        {{ $subprodi->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tingkat">Tingkat</label>
                            <select class="form-control selectric" id="tingkat" name="tingkat">
                                <option value="">Pilih Tingkat</option>
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
                        <div class="form-group">
                            <label for="telp">
                                No. Telepon
                                <small>(opsional)</small>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">+62</div>
                                </div>
                                <input type="text" class="form-control" name="telp" id="telp"
                                    value="{{ old('telp', $user->telp) }}"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alamat">
                                Alamat
                                <small>(opsional)</small>
                            </label>
                            <input type="text" name="alamat" id="alamat" class="form-control"
                                value="{{ old('alamat', $user->alamat) }}">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <br>
                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                data-target="#modalPassword">Reset
                                Password</button>
                        </div>
                        <div class="form-group">
                            <label for="foto">
                                Foto
                                <small>(opsional)</small>
                            </label>
                            <input type="file" name="foto" id="foto" class="form-control"
                                value="{{ old('foto', $user->foto) }}" aria-describedby="foto-help" accept="image/*">
                            <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin
                                diubah.</small>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
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
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary mr-1">
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalPassword">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Yakin reset password <strong>{{ $user->nama }}?</strong>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="{{ url('admin/pengguna/mahasiswa/reset-password/' . $user->id) }}"
                        class="btn btn-primary">Ya</a>
                </div>
            </div>
        </div>
    </div>
@endsection
