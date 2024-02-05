@extends('layouts.app')

@section('title', 'Ubah Satuan')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/satuan') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Satuan</h1>
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
                    <h4>Ubah Satuan</h4>
                </div>
                <form action="{{ url('dev/satuan/' . $satuan->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="nama">Nama Satuan</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama', $satuan->nama) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="singkatan">Singkatan</label>
                            <input type="text" name="singkatan" id="singkatan" class="form-control"
                                value="{{ old('singkatan', $satuan->singkatan) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="kali">Kali</label>
                            <input type="number" name="kali" id="kali" class="form-control"
                                value="{{ old('kali', $satuan->kali) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="kategori">Kategori</label>
                            <select class="custom-select custom-select-sm" name="kategori" id="kategori">
                                <option value="">- Pilih -</option>
                                <option value="volume" {{ old('kategori', $satuan->kategori) == 'volume' ? 'selected' : null }}>Volume</option>
                                <option value="berat" {{ old('kategori', $satuan->kategori) == 'berat' ? 'selected' : null }}>Berat</option>
                                <option value="bahan" {{ old('kategori', $satuan->kategori) == 'bahan' ? 'selected' : null }}>Bahan</option>
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
