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
        <a href="{{ url('profile/' . auth()->user()->id) }}" class="btn btn-outline-white btn-lg btn-icon icon-left">
          <i class="far fa-user"></i> Lengkapi Data
        </a>
      </div>
    </div>
  </div>
  @endif
  <div class="section-body">
    <h2 class="section-title">Data Master</h2>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-dolly-flatbed"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('kalab/admin') }}">
              <div class="card-header">
                <h4>Data Admin</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($admins) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-heart-broken"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('kalab/laboran') }}">
              <div class="card-header">
                <h4>Data Laboran</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($laborans) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-window-close"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('kalab/peminjam') }}">
              <div class="card-header">
                <h4>Data Peminjam</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($peminjams) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-th-list"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('kalab/ruang') }}">
              <div class="card-header">
                <h4>Data Ruang</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($ruangs) }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <h2 class="section-title">Pemasukan</h2>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-dolly-flatbed"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('kalab/stokbarang') }}">
              <div class="card-header">
                <h4>Barang Masuk</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($barangs) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-dolly-flatbed"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('kalab/stokbahan') }}">
              <div class="card-header">
                <h4>Bahan Masuk</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($bahans) }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <h2 class="section-title">Rusak | Hilang | Habis</h2>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-heart-broken"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('kalab/barangrusak') }}">
              <div class="card-header">
                <h4>Barang Rusak</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($barangrusaks) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-window-close"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('kalab/baranghilang') }}">
              <div class="card-header">
                <h4>Barang Hilang</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($baranghilangs) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-th-list"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('kalab/bahanhabis') }}">
              <div class="card-header">
                <h4>Bahan Habis</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($bahanhabises) }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection