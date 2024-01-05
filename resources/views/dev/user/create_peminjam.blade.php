@extends('layouts.app')

@section('title', 'Tambah Peminjam')

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
                    <h4>Tambah Peminjam</h4>
                </div>
                <form action="{{ url('dev/user') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="role" id="role" class="form-control" value="peminjam">
                        <div class="form-group mb-3">
                            <label for="username">
                                NIM
                                <small>(digunakan untuk login)</small>
                            </label>
                            <input type="text" name="username" id="username" class="form-control"
                                value="{{ old('username') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama Mahasiswa</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="subprodi_id">Prodi</label>
                            <select class="custom-select custom-select-sm" name="subprodi_id" id="subprodi_id">
                                <option value="">- Pilih -</option>
                                @foreach ($subprodis as $subprodi)
                                    <option value="{{ $subprodi->id }}"
                                        {{ old('subprodi_id') == $subprodi->id ? 'selected' : '' }}>
                                        {{ $subprodi->jenjang }} {{ $subprodi->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tingkat">Tingkat</label>
                            <select class="custom-select custom-select-sm" name="tingkat" id="tingkat">
                                <option value="">- Pilih -</option>
                                <option value="1" {{ old('tingkat') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ old('tingkat') == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ old('tingkat') == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ old('tingkat') == '4' ? 'selected' : '' }}>4</option>
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
                                    value="{{ old('telp') }}">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat">
                                Alamat
                                <small>(opsional)</small>
                            </label>
                            <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="10" style="height: 80px">{{ old('alamat') }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="foto">
                                Foto
                                <small>(opsional)</small>
                            </label>
                            <input type="file" name="foto" id="foto" class="form-control"
                                value="{{ old('foto') }}" accept="image/*">
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
