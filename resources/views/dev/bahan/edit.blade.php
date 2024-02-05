@extends('layouts.app')

@section('title', 'Ubah Bahan')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/bahan') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Bahan</h1>
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
                    <h4>Ubah Bahan</h4>
                </div>
                <form action="{{ url('dev/bahan/'. $bahan->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="nama">Nama Bahan</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama', $bahan->nama) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="ruang_id">Ruang</label>
                            <select name="ruang_id" id="ruang_id" class="form-control select2">
                                <option value="">Pilih Ruang</option>
                                @foreach ($ruangs as $ruang)
                                    <option value="{{ $ruang->id }}"
                                        {{ old('ruang_id', $bahan->ruang_id) == $ruang->id ? 'selected' : '' }}>
                                        {{ $ruang->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="stok">Stok</label>
                            <input type="number" name="stok" id="stok" class="form-control"
                                value="{{ old('stok', $bahan->stok) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="satuan_id">Satuan</label>
                            <select name="satuan_id" id="satuan_id" class="form-control select2">
                                @foreach ($satuans as $satuan)
                                    <option value="{{ $satuan->id }}"
                                        {{ old('satuan_id', $bahan->satuan_id) == $satuan->id ? 'selected' : '' }}>
                                        {{ ucfirst($satuan->nama) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="keterangan">
                                Keterangan
                                <small>(opsional)</small>
                            </label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control"
                                value="{{ old('keterangan', $bahan->keterangan) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="gambar">
                                Gambar
                                @if ($bahan->gambar)
                                    <small>(kosongkan saja jika tidak ingin diubah)</small>
                                @else
                                    <small>(opsional)</small>
                                @endif
                            </label>
                            <input type="file" name="gambar" id="gambar" class="form-control"
                                value="{{ old('gambar') }}" accept="image/*">
                        </div>
                        @if ($bahan->gambar)
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <div class="chocolat-parent">
                                        <a href="{{ asset('storage/uploads/' . $bahan->gambar) }}" class="chocolat-image"
                                            title="{{ $bahan->nama }}">
                                            <div data-crop-image="h-100">
                                                <img alt="image" src="{{ asset('storage/uploads/' . $bahan->gambar) }}"
                                                    class="rounded w-100">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
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
