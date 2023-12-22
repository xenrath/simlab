@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/pengguna/mahasiswa') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Mahasiswa</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Mahasiswa</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
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
                                    <strong>NIM</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->kode }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Prodi</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->subprodi->jenjang }}
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
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>No. Telepon</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->telp }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Alamat</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->alamat }}
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="col-md-4">
                            <div class="chocolat-parent">
                                @if ($user->foto != null)
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
