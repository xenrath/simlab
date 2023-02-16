@extends('layouts.app')

@section('title', 'Detail Data User')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('/') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Detail Pinjaman</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Detail Data Pinjaman</h4>
        <div class="card-header-action">
          @if ($pinjam->status == 'menunggu')
          <span class="badge badge-warning">Menunggu</span>
          @elseif ($pinjam->status == 'diterima')
          <span class="badge badge-primary">Diterima</span>
          @elseif ($pinjam->status == 'selesai')
          <span class="badge badge-success">Selesai</span>
          @endif
        </div>
      </div>
      <div class="card-body">
        <div class="row p-0">
          <div class="col-md-6 p-0">
            <table class="table">
              <tr>
                <th class="w-25">Tanggal Pinjam</th>
                <td class="w-50">{{ $pinjam->jam_awal }}, {{ date('d-m-Y', strtotime($pinjam->tanggal_awal)) }}</td>
              </tr>
              <tr>
                <th class="w-25">Tanggal Kembali</th>
                <td class="w-50">{{ $pinjam->jam_akhir }}, {{ date('d-m-Y', strtotime($pinjam->tanggal_akhir)) }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-6 p-0">
            <table class="table">
              <tr>
                <th class="w-25">Ruang Lab.</th>
                <td class="w-50">{{ $pinjam->ruang->nama }}</td>
              </tr>
              <tr>
                <th class="w-25">Kalab</th>
                <td class="w-50">
                  @if ($pinjam->kalab != null)
                  {{ $pinjam->kalab }}
                  @else
                  -
                  @endif
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="card-body pt-0">
        <div class="table-responsive-sm">
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Nama Barang</th>
                <th class="text-center">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($barang_pinjams as $barang_pinjam)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $barang_pinjam->barang->nama }}</td>
                <td class="text-center">{{ $barang_pinjam->jumlah }}</td>
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