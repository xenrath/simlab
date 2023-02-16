@extends('layouts.app')

@section('title', 'Tambah Tempat')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('dev/tempat') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Data Tempat</h1>
  </div>
  @if (session('status'))
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
        @foreach (session('status') as $error)
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
        <h4>Tambah Tempat</h4>
      </div>
      <form action="{{ url('dev/tempat') }}" method="POST" autocomplete="off">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="kode">Kode *</label>
                <input type="text" name="kode" id="kode" class="form-control" value="{{ old('kode') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Tempat *</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}">
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn btn-primary mr-1">
            <i class="fas fa-save"></i>
            <span class="d-none d-md-inline">Simpan</span>
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i>
            <span class="d-none d-md-inline">Reset</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection