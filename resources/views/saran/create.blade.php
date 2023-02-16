@extends('layouts.app')

@section('title', 'Saran dan Masukan')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url()->previous() }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Saran dan Masukan</h1>
  </div>
  <div class="section-body">
    <h2 class="section-title">Laporkan kendala Anda saat menggunakan Sistem ini.</h2>
    <p class="section-lead">InsyaAllah bakal kami perbaiki jika memungkinkan hehe.</p>

    @if (session('error'))
    <div class="alert alert-danger alert-has-icon">
      <div class="alert-icon">
        <i class="fas fa-exclamation-circle"></i>
      </div>
      <div class="alert-body">
        <div class="alert-title">Error !</div>
        @foreach (session('error') as $error)
        <p>
          <span class="bullet"></span>&nbsp;
          {{ $error }}
        </p>
        @endforeach
      </div>
    </div>
    @endif

    <div class="card">
      <div class="card-header">
        <h4>Buat Bahan</h4>
      </div>
      <form action="{{ url('saran') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="kategori">Kategori</label>
                <select class="form-control selectric" id="kategori" name="kategori">
                  <option value="">- Pilih Kategori -</option>
                  <option value="saran" {{ old('kategori')=='saran' ? 'selected' : '' }}>Saran</option>
                  <option value="kendala" {{ old('kategori')=='saran' ? 'selected' : '' }}>Kendala</option>
                  <option value="ucapan" {{ old('kategori')=='ucapan' ? 'selected' : '' }}>Ucapan </option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="saran">Saran *</label>
                <textarea class="summernote-simple" id="saran" name="saran">{{ old('saran') }}</textarea>
                @error('saran')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="gambar">Gambar (opsional)</label>
                <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror"
                  value="{{ old('gambar') }}" accept="image/*">
                <small class="form-text text-muted">Tambahkan gambar jika diperlukan.</small>
                @error('gambar')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-paper-plane"></i> Save
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