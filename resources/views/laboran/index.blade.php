@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="section-body">
            @if (auth()->user()->telp == null || auth()->user()->alamat == null)
                <div class="hero bg-primary text-white mb-3">
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
            @endif
            @if (auth()->user()->ruangs->first()->tempat_id == '2')
                {{-- <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('laboran/kelompok/peminjaman') }}">
                                    <div class="card-header">
                                        <h4>Peminjaman Menunggu</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ count($menunggus) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('laboran/kelompok/pengembalian') }}">
                                    <div class="card-header">
                                        <h4>Dalam Peminjaman</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ count($disetujuis) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('laboran/kelompok/riwayat') }}">
                                    <div class="card-header">
                                        <h4>Peminjaman Selesai</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ count($selesais) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            @else
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1 mb-3">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('laboran/peminjaman-new') }}">
                                    <div class="card-header">
                                        <h4>Peminjaman Menunggu</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $menunggu }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1 mb-3">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('laboran/pengembalian-new') }}">
                                    <div class="card-header">
                                        <h4>Dalam Peminjaman</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $proses }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1 mb-3">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('laboran/riwayat-new') }}">
                                    <div class="card-header">
                                        <h4>Riwayat Peminjaman</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $selesai }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1 mb-3">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('laboran/tagihan') }}">
                                    <div class="card-header">
                                        <h4>Tagihan Peminjaman</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $tagihan }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <a href="{{ url('laboran/riwayat-new') }}">
                                    <div class="card-header">
                                        <h4>Data Tagihan</h4>
                                    </div>
                                </a>
                                <div class="card-body">
                                    {{ $selesai }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            @endif
        </div>
    </section>
@endsection
