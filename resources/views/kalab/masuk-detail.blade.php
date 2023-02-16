@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('kalab/masuk') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Detail</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Detail Barang Masuk</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <table class="table">
              <tr>
                <th>Kode</th>
                <td>{{ $barang->kode }}</td>
              </tr>
              <tr>
                <th>Nama Barang</th>
                <td>{{ $barang->nama }}</td>
              </tr>
              <tr>
                <th>Keterangan</th>
                <td>{{ $barang->keterangan }}</td>
              </tr>
              <tr>
                <th>Kategori</th>
                <td>{{ $barang->kategori }}</td>
              </tr>
              <tr>
                <th>Stok</th>
                <td>{{ $barang->stok }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">
            <img src="{{ asset('stisla/assets/img/logo-bhamada.png') }}" alt="Bhamada" class="w-100 border rounded">
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
@endsection