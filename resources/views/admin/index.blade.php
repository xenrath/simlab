@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('admin/user') }}">
                                <div class="card-header">
                                    <h4>Data Peminjam</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $peminjam }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('admin/barang') }}">
                                <div class="card-header">
                                    <h4>Data Barang</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $barang }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('admin/bahan') }}">
                                <div class="card-header">
                                    <h4>Data Bahan</h4>
                                </div>
                            </a>
                            <div class="card-body">
                                {{ $bahan }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
