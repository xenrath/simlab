@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('/') }}" class="btn btn-secondary rounded-0">
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
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Edit Profile</h4>
                </div>
                <form action="{{ url('profile/update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control rounded-0" name="nama" id="nama"
                                value="{{ old('nama', $user->nama) }}">
                        </div>
                        @if (auth()->user()->isPeminjam())
                            <div class="form-group mb-2">
                                <label for="kode">NIM</label>
                                <input type="text" class="form-control rounded-0" id="kode"
                                    value="{{ $user->kode }}" readonly>
                            </div>
                        @endif
                        @if (auth()->user()->isPeminjam())
                            <div class="form-group mb-2">
                                <label for="subprodi">Prodi</label>
                                <input type="text" class="form-control rounded-0" id="subprodi"
                                    value="{{ $user->subprodi->jenjang }} {{ $user->subprodi->nama }}" readonly>
                            </div>
                        @endif
                        <div class="form-group mb-2">
                            <label for="telp">
                                No. Telepon
                                <small class="text-muted">(contoh: 08xxxxxxxxxx)</small>
                            </label>
                            <input type="tel" class="form-control rounded-0" name="telp" id="telp"
                                value="{{ old('telp', $user->telp) }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="password">Password
                                <small>(Kosongkan jika tidak ingin diubah)</small>
                            </label>
                            <input type="password" class="form-control rounded-0" name="password" id="password">
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="submit" class="btn btn-primary rounded-0">Perbarui Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
