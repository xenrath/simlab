@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('kalab/stokbarang') }}" class="btn btn-secondary">
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
      <div class="card-body p-0">
        <div class="row p-4">
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
                <th>Tempat</th>
                <td>{{ $barang->ruang->tempat->nama }} ({{ $barang->ruang->nama }})</td>
              </tr>
              <tr>
                <th>Jumlah Normal</th>
                <td>{{ $barang->normal }}</td>
              </tr>
              <tr>
                <th>Jumlah Rusak</th>
                <td>{{ $barang->rusak }}</td>
              </tr>
              <tr>
                <th>Keterangan</th>
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
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Jumlah Normal</th>
                <th>Jumlah Rusak</th>
                <th>Tanggal Masuk</th>
              </tr>
            </thead>
            <tbody id="data-barang">
              @forelse ($stoks as $stok)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $stok->normal }} {{ $stok->satuan->singkatan }}</td>
                <td>{{ $stok->rusak }} {{ $stok->satuan->singkatan }}</td>
                <td>{{ date('d M Y', strtotime($stok->created_at)) }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center">- Data tidak ditemukan -</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
@endsection