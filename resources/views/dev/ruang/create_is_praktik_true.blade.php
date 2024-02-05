@extends('layouts.app')

@section('title', 'Tambah Ruang')

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
                    <h4>Tambah Ruang</h4>
                </div>
                <form action="{{ url('dev/ruang') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="is_praktik" id="is_praktik" class="form-control"
                            value="1">
                        <div class="form-group mb-3">
                            <label for="nama">Nama Ruang</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="tempat_id">Tempat</label>
                            <select class="custom-select custom-select-sm" id="tempat_id" name="tempat_id">
                                <option value="">- Pilih -</option>
                                @foreach ($tempats as $tempat)
                                    <option value="{{ $tempat->id }}"
                                        {{ old('tempat_id') == $tempat->id ? 'selected' : '' }}>{{ $tempat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="prodi_id">Prodi</label>
                            <select class="custom-select custom-select-sm" id="prodi_id" name="prodi_id">
                                <option value="">- Pilih -</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ ucfirst($prodi->singkatan) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="laboran_id">Laboran</label>
                            <select class="form-control select2" id="laboran_id" name="laboran_id">
                                <option value="">- Pilih Laboran -</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('laboran_id') == $user->id ? 'selected' : '' }}>{{ $user->nama }}
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
