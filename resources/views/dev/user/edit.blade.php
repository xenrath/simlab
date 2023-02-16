@extends('layouts.app')

@section('title', 'Edit Data')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('dev/user') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Data User</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Edit User</h4>
      </div>
      <form action="{{ url('dev/user/' . $user->id) }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
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
            <div class="col-lg-6">
              <div class="form-group">
                <label for="nama">Nama *</label>
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
                <label for="role">Role *</label>
                <select class="form-control selectric @error('role') is-invalid @enderror" name="role" id="role"
                  required>
                  <option value="" {{ old('role')=='' ? 'selected' : '' }}>- Pilih -</option>
                  <option value="kalab" {{ old('role', $user->role)=='kalab' ? 'selected' : '' }}>Kalab</option>
                  <option value="laboran" {{ old('role', $user->role)=='laboran' ? 'selected' : '' }}>Laboran</option>
                  <option value="peminjam" {{ old('role', $user->role)=='peminjam' ? 'selected' : '' }}>Peminjam
                  </option>
                </select>
                @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="telp">No. Telepon *</label>
                <input type="number" name="telp" id="telp" class="form-control @error('telp') is-invalid @enderror"
                  value="{{ old('telp', $user->telp) }}">
                @error('telp')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror"
                  value="{{ old('alamat', $user->alamat) }}">
                @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="password">Password</label>
                <input type="text" name="password" id="password"
                  class="form-control @error('password') is-invalid @enderror">
                <small class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6">
              <div class="chocolat-parent">
                @if ($user->foto != null)
                <a href="{{ asset('storage/uploads/' . $user->foto) }}" class="chocolat-image"
                  title="{{ $user->nama }}">
                  <div data-crop-image="h-100">
                    <img alt="image" src="{{ asset('storage/uploads/' . $user->foto) }}"
                      class="img-fluid img-thumbnail w-100">
                  </div>
                </a>
                @else
                <a href="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="chocolat-image"
                  title="{{ $user->nama }}">
                  <div data-crop-image="h-100">
                    <img alt="image" src="{{ asset('storage/uploads/logo-bhamada1.png') }}"
                      class="img-fluid img-thumbnail">
                  </div>
                </a>
                @endif
              </div>
            </div>
            <div class="col-md-9 col-sm-6">
              <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror"
                  value="{{ old('foto', $user->foto) }}" aria-describedby="foto-help" accept="image/*">
                <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
                @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
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