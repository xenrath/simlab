@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('/') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Profile Saya</h1>
    </div>
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
          <div class="alert-title">GAGAL !</div>
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          <p>
            @foreach (session('error') as $error)
              <span class="bullet"></span>&nbsp;{{ strtoupper($error) }}
              <br>
            @endforeach
          </p>
        </div>
      </div>
    @endif
    <div class="section-body">
      <div class="row mt-sm-4">
        <div class="col-md-5">
          <div class="card profile-widget">
            <div class="profile-widget-header">
              @if (auth()->user()->foto)
                <img alt="{{ auth()->user()->nama }}" src="{{ asset('storage/uploads/' . auth()->user()->foto) }}"
                  class="rounded-circle profile-widget-picture mr-3"
                  style="object-fit: cover; object-position: center; width: 100px; height: 100px;">
                <h6 class="text-primary p-4 text-wrap d-md-block d-none">{{ $user->nama }}</h6>
                <h6 class="text-primary text-center d-md-none d-block">{{ $user->nama }}</h6>
              @else
                <img alt="Bhamada" src="{{ asset('storage/uploads/logo-bhamada1.png') }}"
                  class="rounded-circle profile-widget-picture mr-3"
                  style="object-fit: cover; object-position: center; width: 100px; height: 100px;">
                <h6 class="text-primary p-4 text-wrap d-md-block d-none">{{ $user->nama }}</h6>
                <h6 class="text-primary text-center d-md-none d-block">{{ $user->nama }}</h6>
              @endif
            </div>
            <div class="profile-widget-description">
              <table class="w-100">
                <tr height="50">
                  <td>
                    <strong>Username</strong>
                  </td>
                  <td>:</td>
                  <td class="text-right">{{ $user->username }}</td>
                </tr>
                <tr height="50">
                  <td>
                    <strong>
                      @if (auth()->user()->isPeminjam())
                        NIM
                      @else
                        NIP
                      @endif
                    </strong>
                  </td>
                  <td>:</td>
                  <td class="text-right">
                    @if ($user->kode)
                      {{ $user->kode }}
                    @else
                      -
                    @endif
                  </td>
                </tr>
                <tr height="50">
                  <td>
                    <strong>Status</strong>
                  </td>
                  <td>:</td>
                  <td class="text-right">{{ ucfirst($user->role) }}</td>
                </tr>
                <tr height="50">
                  <td>
                    <strong>No. Telepon</strong>
                  </td>
                  <td>:</td>
                  <td class="text-right">
                    @if ($user->telp)
                      +62{{ $user->telp }}
                    @else
                      -
                    @endif
                  </td>
                </tr>
                <tr height="50">
                  <td>
                    <strong>Alamat</strong>
                  </td>
                  <td>:</td>
                  <td class="text-right">
                    @if ($user->alamat)
                      {{ $user->alamat }}
                    @else
                      -
                    @endif
                  </td>
                </tr>
                @if (auth()->user()->isPeminjam())
                  <tr height="50">
                    <td>
                      <strong>Prodi</strong>
                    </td>
                    <td>:</td>
                    <td class="text-right">
                      @if ($user->subprodi != null)
                        {{ $user->subprodi->jenjang }} {{ $user->subprodi->nama }}
                      @else
                        -
                      @endif
                    </td>
                  </tr>
                @endif
                @if (auth()->user()->isLaboran())
                  <tr height="50">
                    <td class="align-top">
                      <strong>Ruang Lab.</strong>
                    </td>
                    <td class="align-top">:</td>
                    <td class="text-right">
                      @if ($user->ruangs)
                        @foreach ($user->ruangs as $ruang)
                          - {{ $ruang->nama }} <br>
                        @endforeach
                      @else
                        <small>(belum ada ruang lab yang dikaitkan)</small>
                      @endif
                    </td>
                  </tr>
                @endif
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="card">
            <div class="card-header">
              <h4>Edit Profile</h4>
            </div>
            <form action="{{ url('profile/update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="kode">
                    @if (auth()->user()->isPeminjam())
                      NIM
                    @else
                      NIP
                    @endif
                    *
                  </label>
                  <input type="text" class="form-control" name="kode" id="kode"
                    value="{{ old('kode', $user->kode) }}" {{ auth()->user()->isPeminjam()? 'readonly': '' }}>
                </div>
                <div class="form-group">
                  <label for="nama">Nama Lengkap *</label>
                  <input type="text" class="form-control" name="nama" id="nama"
                    value="{{ old('nama', $user->nama) }}" readonly>
                </div>
                @if (auth()->user()->isPeminjam())
                  <div class="form-group">
                    <label for="subprodi_id">Prodi *</label>
                    <select class="form-control" name="subprodi_id" id="subprodi_id" disabled>
                      <option value="" {{ old('subprodi_id') == '' ? 'selected' : '' }}>- Pilih -</option>
                      @foreach ($subprodis as $subprodi)
                        <option value="{{ $subprodi->id }}"
                          {{ old('subprodi_id', $user->subprodi_id) == $subprodi->id ? 'selected' : '' }}>
                          {{ $subprodi->jenjang }} {{ $subprodi->nama }}</option>
                      @endforeach
                    </select>
                  </div>
                @endif
                <div class="form-group">
                  <label for="telp">No. Telepon *</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">+62</div>
                    </div>
                    <input type="text" class="form-control" name="telp" id="telp"
                      value="{{ old('telp', $user->telp) }}"
                      oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                  </div>
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat *</label>
                  <input type="alamat" class="form-control" name="alamat" id="alamat"
                    value="{{ old('alamat', $user->alamat) }}">
                </div>
                <div class="form-group">
                  <label for="foto">Foto Profile (opsional)</label>
                  <input type="file" class="form-control" name="foto" id="foto" accept="image/*"
                    aria-describedby="foto-help">
                  @if (auth()->user()->foto)
                    <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
                  @endif
                </div>
                <hr class="my-4">
                <div class="form-group">
                  <label for="username">Username *</label>
                  <input type="text" class="form-control" name="username" id="username"
                    value="{{ old('username', $user->username) }}">
                </div>
                <div class="form-group">
                  <label for="password">Password (opsional)</label>
                  <input type="password" class="form-control" name="password" id="password">
                  <small class="form-text text-muted">Ubah password demi keamanan Anda.</small>
                </div>
              </div>
              <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Update
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
