@extends('layouts.app')

@section('title', 'Tambah Laboran')

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
                    <h4>Tambah Laboran</h4>
                </div>
                <form action="{{ url('dev/user') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="role" id="role" class="form-control" value="laboran">
                        <div class="form-group mb-3">
                            <label for="username">
                                Username
                                <small>(digunakan untuk login)</small>
                            </label>
                            <input type="text" name="username" id="username" class="form-control"
                                value="{{ old('username') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama Laboran</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="prodi_id">Prodi</label>
                            <select class="custom-select custom-select-sm" name="prodi_id" id="prodi_id">
                                <option value="">- Pilih -</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ ucfirst($prodi->singkatan) }}</option>
                                @endforeach
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
