@extends('layouts.app')

@section('title', 'Tambah Ruangan')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/ruang') }}" class="btn btn-secondary">
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
        <h4>Tambah Ruangan</h4>
      </div>
      <form action="{{ url('admin/ruang') }}" method="POST" autocomplete="off">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Ruangan *</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="prodi">Prodi *</label>
                <select class="form-control selectric" id="prodi" name="prodi">
                  <option value="">- Pilih Prodi -</option>
                  <option value="keperawatan" {{ old('prodi')=='keperawatan' ? 'selected' : '' }}>Keperawatan</option>
                  <option value="kebidanan" {{ old('prodi')=='kebidanan' ? 'selected' : '' }}>Kebidanan</option>
                  <option value="k3" {{ old('prodi')=='k3' ? 'selected' : '' }}>K3</option>
                  <option value="farmasi" {{ old('prodi')=='farmasi' ? 'selected' : '' }}>Farmasi</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="laboran_id">Laboran *</label>
                <select class="form-control select2" id="laboran_id" name="laboran_id">
                  <option value="">- Pilih Laboran -</option>
                  @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ old('laboran_id')==$user->id ? 'selected' : '' }}>{{
                    $user->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn btn-success mr-1">
            <i class="fas fa-paper-plane"></i>
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