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
        <h4>Detail Barang Hilang</h4>
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
                <th class="text-center">Jumlah Hilang</th>
                <th>Nama Peminjam</th>
                <th>Tanggal Pinjam</th>
              </tr>
            </thead>
            <tbody id="data-barang">
              @foreach ($habises as $key => $habis)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $habis->barang->kode }}</td>
                <td>{{ $habis->barang->nama }}</td>
                <td class="text-center">{{ $habis->jumlah }}</td>
                <td>{{ $habis->pinjam->peminjam->nama }}</td>
                <td>{{ date('d/m/Y', strtotime($habis->pinjam->tanggal_awal)) }}</td>
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