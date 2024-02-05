@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/mahasiswa') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Mahasiswa</h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
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
                    <h4>Tambah Mahasiswa</h4>
                </div>
                <form action="{{ url('admin/mahasiswa') }}" method="POST" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="kode">NIM</label>
                            <input type="text" name="kode" id="kode" class="form-control"
                                value="{{ old('kode') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama</label>
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
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                    value="{{ old('telp') }}">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>
                                Alamat
                                <small>(opsional)</small>
                            </label>
                            <input type="text" name="alamat" id="alamat" class="form-control"
                                value="{{ old('alamat') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="foto">
                                Foto
                                <small>(opsional)</small>
                            </label>
                            <input type="file" name="foto" id="foto" class="form-control"
                                aria-describedby="foto-help" accept="image/*">
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