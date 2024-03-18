@extends('layouts.app')

@section('title', 'Tambah Prodi')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/prodi') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Prodi</h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <div class="alert-title">Error!</div>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <ul class="px-3 m-0">
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
                    <h4>Tambah Prodi</h4>
                </div>
                <form action="{{ url('dev/prodi/' . $prodi->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="kode">Kode</label>
                            <input type="text" name="kode" id="kode" class="form-control"
                                value="{{ old('kode', $prodi->kode) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama Prodi</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama', $prodi->nama) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="singkatan">Singkatan</label>
                            <input type="text" name="singkatan" id="singkatan" class="form-control"
                                value="{{ old('singkatan', $prodi->singkatan) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="is_prodi">Kategori</label>
                            <select class="custom-select custom-select-sm" name="is_prodi" id="is_prodi">
                                <option value="">- Pilih -</option>
                                <option value="1" {{ old('is_prodi', $prodi->is_prodi) == '1' ? 'selected' : '' }}>
                                    Prodi</option>
                                <option value="0" {{ old('is_prodi', $prodi->is_prodi) == '0' ? 'selected' : '' }}>
                                    Bukan Prodi</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tempat_id">Tempat</label>
                            <select class="custom-select custom-select-sm" name="tempat_id" id="tempat_id">
                                <option value="">- Pilih -</option>
                                @foreach ($tempats as $tempat)
                                    <option value="{{ $tempat->id }}"
                                        {{ old('tempat_id', $prodi->tempat_id) == $tempat->id ? 'selected' : '' }}>{{ $tempat->nama }}
                                    </option>
                                @endforeach
                            </select>
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
