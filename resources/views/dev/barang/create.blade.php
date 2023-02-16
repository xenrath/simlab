@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('dev/barang') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Tambah Barang</h1>
  </div>
  @if (session('error'))
  <div class="alert alert-danger alert-dismissible show fade">
    <div class="alert-body">
      <div class="alert-title">GAGAL !</div>
      <button class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
      <p>
        @foreach (session('error') as $error)
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
        <h4>Buat Barang</h4>
      </div>
      <form action="{{ url('dev/barang') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Barang *</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="ruang_id">Ruang *</label>
                <select name="ruang_id" id="ruang_id" class="form-control select2">
                  <option value="">- Pilih Ruang -</option>
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
                <label for="normal">Jumlah Baik *</label>
                <input type="number" name="normal" id="normal" class="form-control" value="{{ old('normal') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="rusak">Jumlah Rusak *</label>
                <input type="number" name="rusak" id="rusak" class="form-control" value="{{ old('rusak') }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="satuan_id">Satuan *</label>
                <select name="satuan_id" id="satuan_id" class="form-control selectric">
                  <option value="6">Pcs</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="keterangan">Keterangan (opsional)</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control"
                  value="{{ old('keterangan') }}">
              </div>
            </div>
          </div>
          <div class="row">
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