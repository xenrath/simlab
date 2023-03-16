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
          <h4>Tambah User</h4>
        </div>
        <form action="{{ url('admin/user') }}" method="POST" autocomplete="off"
          enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}">
            </div>
            <div class="form-group">
              <label for="nama">Nama</label>
              <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}">
            </div>
            <div class="form-group">
              <label for="role">Role</label>
              <select class="form-control selectric" name="role" id="role">
                <option value="peminjam" {{ old('role') == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                <option value="laboran" {{ old('role') == 'laboran' ? 'selected' : '' }}>Laboran</option>
              </select>
            </div>
            <div class="form-group">
              <label for="telp">No. Telepon</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">+62</div>
                </div>
                <input type="text" class="form-control" name="telp" id="telp" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="{{ old('telp') }}">
              </div>
            </div>
            <div class="form-group">
              <label>Alamat</label>
              <input type="text" name="alamat" id="alamat" class="form-control" value="{{ old('alamat') }}">
            </div>
            <div class="form-group">
              <label for="foto">Foto</label>
              <input type="file" name="foto" id="foto" class="form-control" aria-describedby="foto-help" accept="image/*">
              <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="reset" class="btn btn-secondary mr-1">
              Reset
            </button>
            <button type="submit" class="btn btn-primary">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection