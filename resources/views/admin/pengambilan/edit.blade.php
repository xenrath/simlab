@extends('layouts.app')

@section('title', 'Ubah Data Barang')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/barang') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Ubah Data Barang</h1>
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
        <h4>Ubah Barang</h4>
      </div>
      <form action="{{ url('admin/barang/' . $barang->id) }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="kode">Nomor Inventaris *</label>
                <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror"
                  value="{{ old('kode', $barang->kode) }}">
                @error('kode')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Barang *</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama', $barang->nama) }}">
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tempat">Tempat Barang *</label>
                <select name="tempat" id="tempat" class="form-control selectric">
                  <option value="">- Pilih Tempat -</option>
                  <option value="lab" {{ old('tempat', $barang->tempat)=='lab' ? 'selected' : null }}>Lab. Terpadu
                  </option>
                  <option value="farmasi" {{ old('tempat', $barang->tempat)=='farmasi' ? 'selected' : null }}>Gedung
                    Farmasi
                  </option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="keterangan">Keterangan (opsional)</label>
                <input type="text" name="keterangan" id="keterangan"
                  class="form-control @error('keterangan') is-invalid @enderror"
                  value="{{ old('keterangan', $barang->keterangan) }}">
                @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="gambar">
                  Gambar
                  @if ($barang->gambar)
                  (opsional)
                  @else
                  *
                  @endif
                </label>
                <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror"
                  aria-describedby="foto-help">
                @if ($barang->gambar)
                <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
                @endif
                @error('gambar')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          @if ($barang->gambar)
          <div class="row">
            <div class="col-md-3 col-sm-6">
              <div class="chocolat-parent">
                <a href="{{ asset('storage/uploads/' . $barang->gambar) }}" class="chocolat-image"
                  title="{{ $barang->nama }}">
                  <div data-crop-image="h-100">
                    <img alt="image" src="{{ asset('storage/uploads/' . $barang->gambar) }}"
                      class="img-fluid rounded w-100">
                  </div>
                </a>
              </div>
            </div>
          </div>
          @endif
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