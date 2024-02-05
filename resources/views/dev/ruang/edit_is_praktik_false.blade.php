@extends('layouts.app')

@section('title', 'Edit Ruang')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/ruang') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Ruang</h1>
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
                    <h4>Edit Ruang</h4>
                </div>
                <form action="{{ url('dev/ruang/' . $ruang->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="nama">Nama Ruang</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama', $ruang->nama) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="tempat_id">Tempat</label>
                            <select class="custom-select custom-select-sm" id="tempat_id" name="tempat_id">
                                <option value="">- Pilih -</option>
                                @foreach ($tempats as $tempat)
                                    <option value="{{ $tempat->id }}"
                                        {{ old('tempat_id', $ruang->tempat_id) == $tempat->id ? 'selected' : '' }}>
                                        {{ $tempat->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="prodi_id">Prodi</label>
                            <select class="custom-select custom-select-sm" id="prodi_id" name="prodi_id">
                                <option value="">- Pilih -</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id', $ruang->prodi_id) == $prodi->id ? 'selected' : '' }}>
                                        {{ ucfirst($prodi->singkatan) }}</option>
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
