@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Detail Barang Rusak</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <tr></tr>
          </table>
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th class="text-center">Jumlah Rusak</th>
                <th>Nama Peminjam</th>
                <th>Tanggal Pinjam</th>
              </tr>
            </thead>
            <tbody id="data-barang">
              @foreach ($rusaks as $key => $rusak)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $rusak->barang->kode }}</td>
                <td>{{ $rusak->barang->nama }}</td>
                <td class="text-center">{{ $rusak->rusak }}</td>
                <td>{{ $rusak->pinjam->peminjam->nama }}</td>
                <td>{{ date('d/m/Y', strtotime($rusak->pinjam->tanggal_awal)) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
@endsection