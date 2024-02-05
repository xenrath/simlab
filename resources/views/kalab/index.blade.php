@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        @if (auth()->user()->telp == null)
            <div class="hero bg-primary text-white">
                <div class="hero-inner">
                    <h2>Selamat Datang, {{ ucfirst(auth()->user()->nama) }}!</h2>
                    <p class="lead">Untuk keperluan Anda, harap lengkapi data diri Anda terlebih dahulu.</p>
                    <div class="mt-4">
                        <a href="{{ url('profile/' . auth()->user()->id) }}"
                            class="btn btn-outline-white btn-lg btn-icon icon-left">
                            <i class="far fa-user"></i> Lengkapi Data
                        </a>
                    </div>
                </div>
            </div>
        @endif
        <div class="section-body">
            <h2 class="section-title my-3">Pengguna</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 mb-3">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <a href="{{ url('kalab/laboran') }}">
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
                            <a href="{{ url('kalab/peminjam') }}">
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
                            <a href="{{ url('kalab/tamu') }}">
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
