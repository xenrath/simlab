@extends('layouts.app')

@section('title', 'Detail Laboran')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('kalab/laboran') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Data Pengguna</h1>
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
                                    <strong>Nama</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>No. Telepon</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $user->telp != null ? '+62' . $user->telp : '-' }}
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
                                    <strong>Ruang Lab</strong>
                                </div>
                                <div class="col-md-8">
                                    @if (count($user->ruangs) > 0)
                                        @foreach ($user->ruangs as $ruang)
                                            <span>{{ $ruang->nama }}</span>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    @else
                                        <small>(belum ada ruang lab yang dikaitkan)</small>
                                    @endif
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
                                                    class="rounded w-100">
                                            </div>
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="chocolat-image"
                                            title="{{ $user->nama }}">
                                            <div data-crop-image="h-100">
                                                <img alt="image" src="{{ asset('storage/uploads/logo-bhamada1.png') }}"
                                                    class="rounded w-100">
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
