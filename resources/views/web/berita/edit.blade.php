@extends('layouts.app')

@section('title', 'Tambah Berita')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('web/berita') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Data Berita</h1>
    </div>
    @if (session('error'))
      <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
        <div class="alert-icon">
          <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="alert-body">
          <div class="alert-title">Error!</div>
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          <p>
            @foreach (session('error') as $error)
              <span class="bullet"></span>&nbsp;{{ $error }}
              <br>
            @endforeach
          </p>
        </div>
      </div>
    @endif
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Tambah Berita</h4>
        </div>
        <form action="{{ url('web/berita') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="form-group">
              <label for="judul">Judul *</label>
              <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $berita->judul) }}">
            </div>
            <div class="form-group">
              <label for="gambar">Gambar (opsional)</label>
              <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
              <label for="isi">Isi *</label>
              <textarea class="summernote" name="isi" id="isi">{{ old('isi', $berita->isi) }}</textarea>
            </div>
          </div>
          <div class="card-footer float-right">
            <button type="submit" class="btn btn-primary">
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