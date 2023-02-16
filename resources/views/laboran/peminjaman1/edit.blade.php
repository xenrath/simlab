@extends('layouts.app')

@section('title', 'Edit Laboran')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('laboran/pinjam') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Laboran</h1>
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
        <h4>Edit Laboran</h4>
      </div>
      <form action="{{ url('admin/laboran/' . $user->id) }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" name="username" id="username"
                  class="form-control @error('username') is-invalid @enderror"
                  value="{{ old('username', $user->username) }}">
                @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama', $user->nama) }}">
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="telp">No. Telepon (opsional)</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">+62</div>
                  </div>
                  <input type="text" class="form-control @error('telp') is-invalid @enderror" name="telp" id="telp"
                    value="{{ old('telp', $user->telp) }}">
                  @error('telp')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="alamat">Alamat (opsional)</label>
                <textarea name="alamat" id="alamat" cols="30" rows="10"
                  class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="foto">Foto (opsional)</label>
                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror"
                  value="{{ old('foto', $user->foto) }}" aria-describedby="foto-help" accept="image/*">
                @if ($user->foto)
                <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
                @endif
                @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          @if ($user->foto != null)
          <div class="row">
            <div class="col-md-3 col-sm-6">
              <div class="chocolat-parent">
                <a href="{{ asset('storage/uploads/' . $user->foto) }}" class="chocolat-image"
                  title="{{ $user->nama }}">
                  <div data-crop-image="h-100">
                    <img alt="image" src="{{ asset('storage/uploads/' . $user->foto) }}"
                      class="img-fluid img-thumbnail w-100">
                  </div>
                </a>
              </div>
            </div>
          </div>
          @endif
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