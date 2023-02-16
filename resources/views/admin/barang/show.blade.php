@extends('layouts.app')

@section('title', 'Detail Data Barang')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/barang') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Detail Data Barang</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Detail Barang</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12">
            <table class="table">
              <tr>
                <th>Kode</th>
                <td>:</td>
                <td>{{ $barang->kode }}</td>
              </tr>
              <tr>
                <th>Nama</th>
                <td>:</td>
                <td>{{ $barang->nama }}</td>
              </tr>
              <tr>
                <th>Tempat</th>
                <td>:</td>
                <td>{{ $barang->ruang->tempat->nama }} ({{ $barang->ruang->nama }})</td>
              </tr>
              <tr>
                <th>Jumlah Normal</th>
                <td>:</td>
                <td>{{ $barang->normal }}</td>
              </tr>
              <tr>
                <th>Jumlah Rusak</th>
                <td>:</td>
                <td>{{ $barang->rusak }}</td>
              </tr>
              <tr>
                <th>Keterangan</th>
                <td>:</td>
                <td>{{ $barang->keterangan }}</td>
              </tr>
            </table>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-8 col-8">
            <div class="chocolat-parent">
              @if ($barang->gambar != null)
              <a href="{{ asset('storage/uploads/' . $barang->gambar) }}" class="chocolat-image"
                title="{{ $barang->nama }}">
                <div data-crop-image="h-100">
                  <img alt="image" src="{{ asset('storage/uploads/' . $barang->gambar) }}" class="rounded w-100">
                </div>
              </a>
              @else
              <a href="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="chocolat-image"
                title="{{ $barang->nama }}">
                <div data-crop-image="h-100">
                  <img alt="image" src="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="rounded w-100">
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