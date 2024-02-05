@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title my-3">Peminjaman</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('admin/proses') }}">
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
                            <a href="{{ url('admin/selesai') }}">
                                <div class="card-header">
                                    <h4>Riwayat Peminjaman</h4>
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
                            <a href="{{ url('admin/tagihan') }}">
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
            <h2 class="section-title my-3">Pengguna</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('admin/mahasiswa') }}">
                                <div class="card-header">
                                    <h4>Mahasiswa</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $peminjam }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('admin/laboran') }}">
                                <div class="card-header">
                                    <h4>Laboran</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $laboran }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('admin/tamu') }}">
                                <div class="card-header">
                                    <h4>Tamu</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $tamu }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
