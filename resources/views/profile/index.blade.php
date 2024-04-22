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
                    <ul class="px-3 mb-0">
                        @foreach (session('error') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Profile</h4>
                </div>
                <form action="{{ url('profile/update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nama">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama" id="nama"
                                        value="{{ old('nama', $user->nama) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kode">
                                        @if (auth()->user()->isPeminjam())
                                            NIM
                                        @else
                                            NIP
                                        @endif
                                    </label>
                                    <input type="text" class="form-control" id="kode" value="{{ $user->kode }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        @if (auth()->user()->isPeminjam())
                            <div class="form-group mb-3">
                                <label for="subprodi">Prodi</label>
                                <input type="text" class="form-control" id="subprodi"
                                    value="{{ $user->subprodi->jenjang }} {{ $user->subprodi->nama }}" readonly>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="telp">No. Telepon</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">+62</div>
                                        </div>
                                        <input type="text" class="form-control" name="telp" id="telp"
                                            value="{{ old('telp', $user->telp) }}"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="alamat">Alamat</label>
                                    <input type="alamat" class="form-control" name="alamat" id="alamat"
                                        value="{{ old('alamat', $user->alamat) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="foto">
                                Foto Profile
                                <small>
                                    @if ($user->foto)
                                        (kosongkan jika tidak ingin diubah)
                                    @else
                                        (opsional)
                                    @endif
                                </small>
                            </label>
                            <input type="file" class="form-control" name="foto" id="foto" accept="image/*">
                            @if ($user->foto)
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="chocolat-parent">
                                            <a href="{{ asset('storage/uploads/' . $user->foto) }}" class="chocolat-image"
                                                title="{{ $user->nama }}">
                                                <img alt="image" src="{{ asset('storage/uploads/' . $user->foto) }}"
                                                    class="rounded"
                                                    style="object-fit: cover; object-position: center; center; width: 120px; height: 120px;">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <hr class="my-4">
                        <div class="form-group mb-3">
                            <label for="password">Password
                                <small>(Kosongkan jika tidak ingin diubah)</small>
                            </label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="submit" class="btn btn-primary">Perbarui Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
