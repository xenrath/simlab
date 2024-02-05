@extends('layouts.app')

@section('title', 'Ubah Barang')

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
                    <h4>Ubah Barang</h4>
                </div>
                <form action="{{ url('admin/barang/' . $barang->id) }}" method="POST" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="kode">Kode</label>
                            <input type="text" name="kode" id="kode" class="form-control"
                                value="{{ old('kode', $barang->kode) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama Barang</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama', $barang->nama) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="ruang_id">Ruang Lab</label>
                            <select name="ruang_id" id="ruang_id" class="form-control select2">
                                <option value="">Pilih Ruang</option>
                                @foreach ($ruangs as $ruang)
                                    <option value="{{ $ruang->id }}"
                                        {{ old('ruang_id', $barang->ruang_id) == $ruang->id ? 'selected' : null }}>
                                        {{ $ruang->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="normal">Jumlah Baik</label>
                            <input type="number" name="normal" id="normal" class="form-control"
                                value="{{ old('normal', $barang->normal) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="rusak">
                                Jumlah Rusak
                                <small>(optional)</small>
                            </label>
                            <input type="number" name="rusak" id="rusak" class="form-control"
                                value="{{ old('rusak', $barang->rusak) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="keterangan">
                                Keterangan
                                <small>(optional)</small>
                            </label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control"
                                value="{{ old('keterangan', $barang->keterangan) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="gambar">
                                Gambar
                                <small>(opsional)</small>
                            </label>
                            <input type="file" name="gambar" id="gambar" class="form-control"
                                value="{{ old('gambar') }}" aria-describedby="foto-help">
                            @if ($barang->gambar)
                                <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin
                                    diubah.</small>
                            @endif
                        </div>
                        @if ($barang->gambar)
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-5 col-5">
                                    <div class="chocolat-parent">
                                        <a href="{{ asset('storage/uploads/' . $barang->gambar) }}" class="chocolat-image"
                                            title="{{ $barang->nama }}">
                                            <div data-crop-image="h-100">
                                                <img alt="image" src="{{ asset('storage/uploads/' . $barang->gambar) }}"
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
