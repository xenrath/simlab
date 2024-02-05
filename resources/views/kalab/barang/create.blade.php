@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/barang') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Barang</h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <div class="alert-title">Gagal !</div>
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
                    <h4>Tambah Barang</h4>
                </div>
                <form action="{{ url('admin/barang') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="nama">Nama Barang</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="ruang_id">Ruang Lab</label>
                            <select name="ruang_id" id="ruang_id" class="form-control select2">
                                <option value="">Pilih Ruang</option>
                                @foreach ($ruangs as $ruang)
                                    <option value="{{ $ruang->id }}"
                                        {{ old('ruang_id') == $ruang->id ? 'selected' : null }}>{{ $ruang->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="normal">Jumlah Baik</label>
                            <input type="number" name="normal" id="normal" class="form-control"
                                value="{{ old('normal') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="rusak">
                                Jumlah Rusak
                                <small>(opsional)</small>
                            </label>
                            <input type="number" name="rusak" id="rusak" class="form-control"
                                value="{{ old('rusak') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="keterangan">
                                Keterangan
                                <small>(opsional)</small>
                            </label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control"
                                value="{{ old('keterangan') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="gambar">
                                Gambar
                                <small>(opsional)</small>
                            </label>
                            <input type="file" name="gambar" id="gambar" class="form-control"
                                value="{{ old('gambar') }}" accept="image/*">
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
