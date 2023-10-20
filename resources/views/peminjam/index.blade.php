@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        {{-- @if (auth()->user()->telp == null || auth()->user()->alamat == null)
            <div class="mb-4">
                <div class="hero bg-primary text-white">
                    <div class="hero-inner">
                        <h2>Selamat Datang, {{ ucfirst(auth()->user()->nama) }}!</h2>
                        <p class="lead">Untuk keperluan Anda, lengkapi data diri Anda terlebih dahulu.</p>
                        <div class="mt-4">
                            <a href="{{ url('profile') }}" class="btn btn-outline-white btn-lg btn-icon icon-left">
                                <i class="far fa-user"></i> Lengkapi Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (auth()->user()->subprodi_id == '5')
            <div class="section-body">
                <h2 class="section-title">Mandiri</h2>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('peminjam/normal/peminjaman') }}">
                                    <div class="card-header">
                                        <h4>Peminjaman Menunggu</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $peminjaman }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('peminjam/normal/pengembalian') }}">
                                    <div class="card-header">
                                        <h4>Dalam Peminjaman</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $pengembalian }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('peminjam/normal/riwayat') }}">
                                    <div class="card-header">
                                        <h4>Riwayat Pinjaman</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $riwayat }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="section-title">Estafet</h2>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('peminjam/estafet/peminjaman') }}">
                                    <div class="card-header">
                                        <h4>Peminjaman Menunggu</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $peminjaman }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('peminjam/estafet/pengembalian') }}">
                                    <div class="card-header">
                                        <h4>Dalam Peminjaman</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $pengembalian }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('peminjam/estafet/riwayat') }}">
                                    <div class="card-header">
                                        <h4>Riwayat Pinjaman</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $riwayat }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="section-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('peminjam/normal/peminjaman-new') }}">
                                    <div class="card-header">
                                        <h4>Peminjaman Menunggu</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $peminjaman }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('peminjam/normal/pengembalian-new') }}">
                                    <div class="card-header">
                                        <h4>Dalam Peminjaman</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $pengembalian }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('peminjam/normal/riwayat-new') }}">
                                    <div class="card-header">
                                        <h4>Riwayat Pinjaman</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $riwayat }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}
    </section>
@endsection
