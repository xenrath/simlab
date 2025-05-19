@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        @if (auth()->user()->telp == null || auth()->user()->alamat == null)
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
        <div class="section-body">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card rounded-0 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('peminjam/farmasi/menunggu') }}">
                                <div class="card-header">
                                    <h4>Menunggu</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $peminjaman }}
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
                            <a href="{{ url('peminjam/farmasi/proses') }}">
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
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('peminjam/farmasi/riwayat') }}">
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
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('peminjam/farmasi/tagihan') }}">
                                <div class="card-header">
                                    <h4>Tagihan Pinjaman</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $tagihan }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
