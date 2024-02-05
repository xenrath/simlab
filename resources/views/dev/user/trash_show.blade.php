@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/user/trash') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Data User</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail User</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Username</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->username }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Nama</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Telp</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($user->telp)
                                        <a href="{{ url('dev/hubungi/' . $user->id) }}"
                                            target="_blank">{{ $user->telp }}</a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Alamat</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->alamat ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Role</strong>
                                </div>
                                <div class="col-md-8">
                                    <div class="badge badge-warning">{{ ucfirst($user->role) }}</div>
                                </div>
                            </div>
                            @if ($user->role == 'peminjam')
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Prodi</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $user->subprodi->nama }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Tingkat</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $user->tingkat }}
                                    </div>
                                </div>
                            @endif
                            @if ($user->role == 'laboran')
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Prodi</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ ucfirst($user->prodi->singkatan) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="chocolat-parent">
                                @if ($user->foto)
                                    <a href="{{ asset('storage/uploads/' . $user->foto) }}" class="chocolat-image"
                                        title="{{ $user->nama }}">
                                        <div data-crop-image="h-100">
                                            <img alt="image" src="{{ asset('storage/uploads/' . $user->foto) }}"
                                                class="img-fluid img-thumbnail">
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
            </div>
        </div>
    </section>
@endsection
