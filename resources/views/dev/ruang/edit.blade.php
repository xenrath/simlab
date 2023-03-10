@extends('layouts.app')

@section('title', 'Edit Ruangan')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('dev/ruang') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Data Ruangan</h1>
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
        <h4>Edit Ruangan</h4>
      </div>
      <form action="{{ url('dev/ruang/' . $ruang->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="kode">Kode *</label>
                <input type="text" name="kode" id="kode" class="form-control" value="{{ old('kode', $ruang->kode) }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Ruangan *</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $ruang->nama) }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tempat_id">Tempat *</label>
                <select class="form-control selectric" id="tempat_id" name="tempat_id">
                  <option value="">- Pilih -</option>
                  @foreach ($tempats as $tempat)
                  <option value="{{ $tempat->id }}" {{ old('tempat_id', $ruang->tempat_id)==$tempat->id ? 'selected' : '' }}>{{
                    $tempat->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="lantai">Lantai *</label>
                <select class="form-control selectric" id="lantai" name="lantai">
                  <option value="">- Pilih Lantai -</option>
                  <option value="L1" {{ old('lantai', $ruang->lantai)=='L1' ? 'selected' : '' }}>Lantai 1</option>
                  <option value="L2" {{ old('lantai', $ruang->lantai)=='L2' ? 'selected' : '' }}>Lantai 2</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="prodi_id">Prodi *</label>
                <select class="form-control selectric" id="prodi_id" name="prodi_id">
                  <option value="">- Pilih -</option>
                  @foreach ($prodis as $prodi)
                  <option value="{{ $prodi->id }}" {{ old('prodi_id', $ruang->prodi_id)==$prodi->id ? 'selected' : '' }}>{{
                    ucfirst($prodi->singkatan) }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="laboran_id">Laboran *</label>
                <select class="form-control select2" id="laboran_id" name="laboran_id">
                  <option value="">- Pilih Laboran -</option>
                  @foreach ($laborans as $laboran)
                  <option value="{{ $laboran->id }}" {{ old('laboran_id', $ruang->laboran_id)==$laboran->id ? 'selected' : '' }}>{{
                    $laboran->nama }}</option>
                  @endforeach
                </select>
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