@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('dev/peminjaman') }}">
                                <div class="card-header">
                                    <h4>Data Peminjaman</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $peminjamans }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('dev/user') }}">
                                <div class="card-header">
                                    <h4>Data User</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $users }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('dev/peminjaman') }}">
                                <div class="card-header">
                                    <h4>Data Prodi</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $prodis }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('dev/user') }}">
                                <div class="card-header">
                                    <h4>Data Sub Prodi</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $sub_prodis }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('dev/peminjaman') }}">
                                <div class="card-header">
                                    <h4>Data Tempat</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $tempats }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('dev/user') }}">
                                <div class="card-header">
                                    <h4>Data Ruang</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $ruangs }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('dev/peminjaman') }}">
                                <div class="card-header">
                                    <h4>Data Barang</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $barangs }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('dev/user') }}">
                                <div class="card-header">
                                    <h4>Data Bahan</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $bahans }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
