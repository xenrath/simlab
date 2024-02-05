@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url()->previous() }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Profile Saya</h1>
  </div>
  <div class="section-body">
    <div class="row mt-sm-4">
      <div class="col-12 col-md-12 col-lg-5">
        <div class="card profile-widget">
          <div class="profile-widget-header">
            @if (auth()->user()->foto)
            <img alt="{{ auth()->user()->nama }}" src="{{ asset('storage/uploads/' . auth()->user()->foto) }}"
              class="rounded-circle profile-widget-picture">
            @else
            <img alt="Bhamada" src="{{ asset('storage/uploads/logo-bhamada.png') }}"
              class="rounded-circle profile-widget-picture">
            @endif
          </div>
          <div class="profile-widget-description">
            <div class="profile-widget-name">{{ $user->nama }}<div class="text-muted d-inline font-weight-normal">
                <div class="slash"></div> {{ $user->kode }}
              </div>
            </div>
            <table class="w-100">
              <tr>
                <td>
                  <strong>No. Telepon</strong>
                </td>
                <td>:</td>
                <td>+62{{ $user->telp }}</td>
              </tr>
              <tr>
                <td>
                  <strong>Alamat</strong>
                </td>
                <td>:</td>
                <td>
                  @if ($user->alamat != null)
                  {{ $user->alamat }}
                  @else
                  -
                  @endif
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-12 col-lg-7">
        <div class="card">
          <div class="card-header">
            <h4>Edit Profile</h4>
          </div>
          <form action="{{ url('profile-update/' . auth()->user()->id) }}" method="POST" class="needs-validation"
            novalidate="" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-6 col-12">
                  <label for="kode">Kode</label>
                  <input type="text" class="form-control @error('kode') is-invalid @enderror" name="kode" id="kode"
                    value="{{ $user->kode }}">
                  @error('kode')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-6 col-12">
                  <label for="nama">Nama Lengkap</label>
                  <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama"
                    value="{{ $user->nama }}">
                  @error('nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-5 col-12">
                  <label for="telp">No. Telepon</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">+62</div>
                    </div>
                    <input type="text" class="form-control" name="telp" id="telp" value="{{ $user->telp }}">
                  </div>
                  @error('telp')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-7 col-12">
                  <label for="alamat">Alamat</label>
                  <input type="alamat" class="form-control @error('alamat') is-invalid @enderror" name="alamat"
                    id="alamat" value="{{ $user->alamat }}">
                  @error('alamat')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="row">
                <div class="form-group col-12">
                  <label for="foto">Foto Profile</label>
                  <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" id="foto"
                    aria-describedby="foto-help">
                  @if (!auth()->user()->foto)
                  <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
                  @endif
                  @error('foto')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="row">
                <div class="form-group mb-0 col-12">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" name="password" id="password">
                  @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection