@extends('layouts.app')

@section('title', 'Edit User')

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
          <h4>Edit User</h4>
        </div>
        <form action="{{ url('admin/user/' . $user->id) }}" method="POST" autocomplete="off"
          enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="card-body">
            @if ($user->kode != null)
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}"
                  disabled>
              </div>
            @endif
            <div class="form-group">
              <label for="nama">Nama</label>
              <input type="text" name="nama" id="nama" class="form-control"
                value="{{ old('nama', $user->nama) }}">
            </div>
            <div class="form-group">
              <label for="role">Role</label>
              <select class="form-control selectric" name="role" id="role">
                <option value="peminjam" {{ old('role', $user->role) == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                <option value="laboran" {{ old('role', $user->role) == 'laboran' ? 'selected' : '' }}>Laboran</option>
              </select>
            </div>
            <div class="form-group">
              <label for="telp">No. Telepon</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">+62</div>
                </div>
                <input type="text" class="form-control" name="telp" id="telp"
                  value="{{ old('telp', $user->telp) }}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
              </div>
            </div>
            <div class="form-group">
              <label for="alamat">
                @if ($user->kode != null)
                  Alamat
                @else
                  Instansi
                @endif
              </label>
              <input type="text" name="alamat" id="alamat" class="form-control"
                value="{{ old('alamat', $user->alamat) }}">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <br>
              <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalPassword">Reset
                Password</button>
            </div>
            <div class="form-group">
              <label for="foto">Foto</label>
              <input type="file" name="foto" id="foto" class="form-control"
                value="{{ old('foto', $user->foto) }}" aria-describedby="foto-help" accept="image/*">
              <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
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
  <div class="modal fade" tabindex="-1" role="dialog" id="modalPassword">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reset Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Yakin reset password <strong>{{ $user->nama }}?</strong>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <a href="{{ url('admin/user/reset-password/' . $user->id) }}" class="btn btn-primary">Ya</a>
        </div>
      </div>
    </div>
  </div>
@endsection
