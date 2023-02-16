@extends('layouts.app')

@section('title', 'Ubah Barang')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('dev/barang') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Ubah Barang</h1>
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
        <h4>Ubah Barang</h4>
      </div>
      <form action="{{ url('dev/barang/' . $barang->id) }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Barang *</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $barang->nama) }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="ruang_id">Ruang *</label>
                <select name="ruang_id" id="ruang_id" class="form-control select2">
                  <option value="">Pilih Ruang</option>
                  @foreach ($ruangs as $ruang)
                  <option value="{{ $ruang->id }}" {{ old('ruang_id', $barang->ruang_id)==$ruang->id ? 'selected' : null
                    }}>{{ $ruang->nama
                    }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control"
                  value="{{ old('keterangan', $barang->keterangan) }}">
              </div>
            </div>
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
                <input type="file" name="gambar" id="gambar" class="form-control" value="{{ old('gambar') }}"
                  aria-describedby="foto-help">
                @if ($barang->gambar)
                <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
                @endif
              </div>
            </div>
          </div>
          @if ($barang->gambar)
          <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-5 col-5">
              <div class="chocolat-parent">
                <a href="{{ asset('storage/uploads/' . $barang->gambar) }}" class="chocolat-image"
                  title="{{ $barang->nama }}">
                  <div data-crop-image="h-100">
                    <img alt="image" src="{{ asset('storage/uploads/' . $barang->gambar) }}" class="rounded w-100">
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