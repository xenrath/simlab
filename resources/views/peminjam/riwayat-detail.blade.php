@extends('layouts.app')

@section('title', 'Detail Data User')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('peminjam/riwayat') }}" class="btn btn-secondary">
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
          @if ($pinjam->status == 'dibatalkan')
          <span class="badge badge-danger">Dibatalkan</span>
          @elseif ($pinjam->status == 'ditolak')
          <span class="badge badge-danger">Ditolak</span>
          @elseif ($pinjam->status == 'selesai')
          <span class="badge badge-danger">Selesai</span>
          @endif
        </div>
      </div>
      <div class="card-body">
        <div class="row p-0">
          <div class="col-md-6 p-0">
            <table class="table">
              <tr>
                <th class="w-25">Waktu Pinjam</th>
                <td class="w-50">{{ $pinjam->jam_awal }}, {{ date('d-m-Y', strtotime($pinjam->tanggal_awal)) }}</td>
              </tr>
              <tr>
                <th class="w-25">Waktu Kembali</th>
                <td class="w-50">{{ $pinjam->jam_akhir }}, {{ date('d-m-Y', strtotime($pinjam->tanggal_akhir)) }}</td>
              </tr>
              <tr>
                <th class="w-25">Keterangan</th>
                <td class="w-50">
                  @if ($pinjam->keterangan)
                  {{ $pinjam->keterangan }}</td>
                @else
                -
                @endif
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
                <th class="w-25">Laboran</th>
                <td class="w-50">{{ $pinjam->laboran->nama }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive-sm">
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($detailpinjams as $detailpinjam)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $detailpinjam->barang->nama }}</td>
                <td>{{ ucfirst($detailpinjam->barang->kategori) }}</td>
                <td>{{ $detailpinjam->jumlah }} {{ ucfirst($detailpinjam->satuan->nama) }}</td>
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