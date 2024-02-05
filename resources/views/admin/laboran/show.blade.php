@extends('layouts.app')

@section('title', 'Detail Laboran')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/pengguna/laboran') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Laboran</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Laboran</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Nama Laboran</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Prodi</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ ucfirst($user->prodi->singkatan ?? '-') }}
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>No. Telepon</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($user->telp)
                                        <a href="{{ url('admin/hubungi/' . $user->id) }}" target="_blank">
                                            {{ $user->telp }}
                                        </a>
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
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Ruang</strong>
                                </div>
                                <div class="col-md-8">
                                    <ul class="px-3 mb-0">
                                        @foreach ($user->ruangs as $ruang)
                                            <li>{{ $ruang->nama }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-4 border rounded">
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
        </div>
    </section>
@endsection
