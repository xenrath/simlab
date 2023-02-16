@extends('layouts.app')

@section('title', 'Edit Data User')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('user') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Edit Data Admin</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Ubah User</h4>
      </div>
      <form action="{{ url('user/' . $user->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="kode">Kode *</label>
                <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror"
                  value="{{ old('kode', $user->kode) }}">
                @error('kode')
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
            <div class="col-lg-6">
              <div class="form-group">
                <label for="role">Role *</label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                  <option value="">- Pilih -</option>
                  <option value="admin" {{ old('role', $user->role)=='admin' ? 'selected' : null }}>Admin</option>
                  <option value="kalab" {{ old('role', $user->role)=='kalab' ? 'selected' : null }}>Kalab</option>
                  <option value="laboran" {{ old('role', $user->role)=='laboran' ? 'selected' : null }}>Laboran</option>
                  <option value="peminjam" {{ old('role', $user->role)=='peminjam' ? 'selected' : null }}>Peminjam
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
                <label for="foto">Foto</label>
                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror"
                  value="{{ old('foto', $user->foto) }}" aria-describedby="foto-help">
                <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
                @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-5 col-5">
              @if ($user->foto != null)
              <img src="{{ asset('storage/uploads/' . $user->foto) }}" alt="" class="img-thumbnail rounded w-100">
              @else
              <img src="{{ asset('storage/uploads/logo-bhamada.jpg') }}" alt="" class="img-thumbnail rounded w-100">
              @endif
            </div>
            <div class="col-lg-9 col-md-8 col-sm-7 col-xs-7 col-7"></div>
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