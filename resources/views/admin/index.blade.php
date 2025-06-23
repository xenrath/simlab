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
                    <div class="card card-statistic-1 rounded-0 mb-3">
                        <div class="card-icon rounded-0 bg-primary">
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
                    <div class="card card-statistic-1 rounded-0 mb-3">
                        <div class="card-icon rounded-0 bg-primary">
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
                    <div class="card card-statistic-1 rounded-0 mb-3">
                        <div class="card-icon rounded-0 bg-primary">
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
                    <div class="card card-statistic-1 rounded-0 mb-3">
                        <div class="card-icon rounded-0 bg-primary">
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
                    <div class="card card-statistic-1 rounded-0 mb-3">
                        <div class="card-icon rounded-0 bg-primary">
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
                    <div class="card card-statistic-1 rounded-0 mb-3">
                        <div class="card-icon rounded-0 bg-primary">
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
            <div class="mt-4 text-right">
                <div
                    class="d-flex flex-column flex-md-row flex-md-wrap align-items-end align-items-md-start justify-content-md-end">
                    <a href="{{ url('admin/form-peminjaman-lab') }}"
                        class="btn btn-info rounded-0 mb-2 ml-auto ml-md-0 mr-md-2" target="_blank">
                        <i class="fas fa-file-alt"></i> Form Peminjaman Lab
                    </a>
                    <a href="{{ url('admin/form-jurnal-praktikum') }}"
                        class="btn btn-info rounded-0 mb-2 ml-auto ml-md-0 mr-md-2" target="_blank">
                        <i class="fas fa-file-alt"></i> Form Jurnal Praktikum
                    </a>
                    <a href="{{ url('admin/form-rekap-jurnal') }}" class="btn btn-info rounded-0 mb-2 ml-auto ml-md-0"
                        target="_blank">
                        <i class="fas fa-file-alt"></i> Form Rekap Jurnal
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
