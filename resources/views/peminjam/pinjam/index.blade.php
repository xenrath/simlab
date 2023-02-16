@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('peminjam/pinjam') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Pinjam Barang</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Barang (Lab. {{ $prodi->nama }})</h4>
        <div class="card-header-action">
          <a href="{{ url('peminjam/pinjam/' . lcfirst($prodi->nama)) . '/create' }}" class="btn btn-primary">Pinjam Barang</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="table-1">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
              </tr>
            </thead>
            <tbody>
              @foreach($barangs as $barang)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $barang->kode }}</td>
                <td>{{ $barang->nama }}</td>
                <td>{{ ucfirst($barang->kategori) }}</td>
                <td>{{ $barang->stok }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection