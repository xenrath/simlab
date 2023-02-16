@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/user') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Data User</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Tambah User</h4>
      </div>
      <form action="{{ url('admin/user') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="kode">Kode *</label>
                <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror"
                  value="{{ old('kode') }}">
                @error('kode')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama *</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama') }}">
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
                <select class="form-control selectric @error('role') is-invalid @enderror" name="role" id="role" required>
                  <option value="" {{ old('role')=='' ? 'selected' : '' }}>- Pilih -</option>
                  <option value="kalab" {{ old('role')=='kalab' ? 'selected' : '' }}>Kalab</option>
                  <option value="laboran" {{ old('role')=='laboran' ? 'selected' : '' }}>Laboran</option>
                  <option value="peminjam" {{ old('role')=='peminjam' ? 'selected' : '' }}>Peminjam</option>
                </select>
                @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="telp">No. Telepon *</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">+62</div>
                  </div>
                  <input type="text" class="form-control @error('telp') is-invalid @enderror" name="telp" id="telp" value="{{ old('telp') }}" required>
                </div>
                @error('telp')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror"
                  value="{{ old('alamat') }}">
                @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror"
                  value="{{ old('foto') }}" accept="image/*">
                @error('foto')
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