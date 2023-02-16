@extends('layouts.app')

@section('title', 'Edit Ruangan')

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
        <h4>Edit Ruangan</h4>
      </div>
      <form action="{{ url('admin/ruang/' . $ruang->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('put')
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Ruangan *</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama', $ruang->nama) }}">
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="prodi">Prodi *</label>
                <select class="form-control selectric @error('prodi') is-invalid @enderror" name="prodi" id="prodi"
                  required>
                  <option value="">- Pilih Prodi -</option>
                  <option value="keperawatan" {{ old('prodi', $ruang->prodi)=='keperawatan' ? 'selected' : ''
                    }}>Keperawatan</option>
                  <option value="kebidanan" {{ old('prodi', $ruang->prodi)=='kebidanan' ? 'selected' : '' }}>Kebidanan
                  </option>
                  <option value="k3" {{ old('prodi', $ruang->prodi)=='k3' ? 'selected' : '' }}>K3</option>
                  <option value="farmasi" {{ old('prodi', $ruang->prodi)=='farmasi' ? 'selected' : '' }}>Farmasi
                  </option>
                </select>
                @error('prodi')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="laboran_id">Laboran *</label>
                <select class="form-control select2" name="laboran_id" id="laboran_id">
                  <option value="">- Pilih Laboran -</option>
                  @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ old('laboran_id', $ruang->laboran_id)==$user->id ? 'selected' : '' }}>{{
                    $user->nama }}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn btn-success mr-1">
            <i class="fas fa-save"></i>
            <span class="d-none d-md-inline">&nbsp;Simpan</span>
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i>
            <span class="d-none d-md-inline">&nbsp;Reset</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection