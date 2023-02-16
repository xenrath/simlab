@extends('layouts.app')

@section('title', 'Tambah Bahan')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/bahan') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Tambah Bahan</h1>
  </div>
  @if (session('status'))
  <div class="alert alert-danger alert-dismissible show fade">
    <div class="alert-body">
      <div class="alert-title">GAGAL !</div>
      <button class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
      <p>
        @foreach (session('status') as $error)
        <span class="bullet"></span>&nbsp;{{ strtoupper($error) }}
        <br>
        @endforeach
      </p>
    </div>
  </div>
  @endif
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Buat Bahan</h4>
      </div>
      <form action="{{ url('admin/bahan') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Bahan *</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="ruang_id">Ruang / Lab *</label>
                <select name="ruang_id" id="ruang_id" class="form-control select2">
                  <option value="">Pilih Ruang</option>
                  @foreach ($ruangs as $ruang)
                  <option value="{{ $ruang->id }}" {{ old('ruang_id')==$ruang->id ? 'selected' : null }}>{{ $ruang->nama
                    }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="stok">Stok *</label>
                <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="satuan_id">Satuan *</label>
                <select name="satuan_id" id="satuan_id" class="form-control selectric">
                  @foreach ($satuans as $satuan)
                  <option value="{{ $satuan->id }}">{{ ucfirst($satuan->nama) }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="keterangan">Keterangan (opsional)</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control"
                  value="{{ old('keterangan') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="gambar">Gambar *</label>
                <input type="file" name="gambar" id="gambar" class="form-control" value="{{ old('gambar') }}"
                  accept="image/*">
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer float-right">
          <button type="submit" class="btn btn-primary mr-1">
            <i class="fas fa-save"></i> Simpan
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Reset
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection