@extends('layouts.app')

@section('title', 'Detail Data Bahan')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/bahan') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Detail Data Bahan</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Detail Bahan</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12">
            <table class="table">
              <tr>
                <th>Kode</th>
                <td>:</td>
                <td>{{ $bahan->kode }}</td>
              </tr>
              <tr>
                <th>Nama</th>
                <td>:</td>
                <td>{{ $bahan->nama }}</td>
              </tr>
              <tr>
                <th>Tempat</th>
                <td>:</td>
                <td>{{ $bahan->ruang->tempat->nama }} ({{ $bahan->ruang->nama }})</td>
              </tr>
              <tr>
                <th>Stok</th>
                <td>:</td>
                <td>{{ $bahan->stok }} {{ $bahan->satuan->singkatan }}</td>
              </tr>
              <tr>
                <th>Keterangan</th>
                <td>:</td>
                <td>{{ $bahan->keterangan }}</td>
              </tr>
            </table>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-8 col-8">
            <div class="chocolat-parent">
              @if ($bahan->gambar == "")
              <a href="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="chocolat-image"
                title="{{ $bahan->nama }}">
                <div data-crop-image="h-100">
                  <img alt="image" src="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="rounded w-100">
                </div>
              </a>
              @else
              <a href="{{ asset('storage/uploads/' . $bahan->gambar) }}" class="chocolat-image"
                title="{{ $bahan->nama }}">
                <div data-crop-image="h-100">
                  <img alt="image" src="{{ asset('storage/uploads/' . $bahan->gambar) }}" class="rounded w-100">
                </div>
              </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection