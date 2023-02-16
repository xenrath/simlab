@extends('layouts.app')

@section('title', 'Detail Data User')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('peminjam/peminjaman') }}" class="btn btn-secondary">
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
          @php
          $tanggal_awal = date('d/m/Y', strtotime($pinjam->tanggal_awal));
          $tanggal_akhir = date('d/m/Y', strtotime($pinjam->tanggal_akhir));
          $jam_awal = $pinjam->jam_awal;
          $jam_akhir = $pinjam->jam_akhir;
          $now = Carbon\Carbon::now();
          $expire = date('Y-m-d G:i:s', strtotime($pinjam->tanggal_awal . $jam_awal));
          @endphp
          @if ($now > $expire)
          <span class="badge badge-danger">Kadaluarsa</span>
          @else
          <span class="badge badge-warning">Menunggu</span>
          @endif
        </div>
      </div>
      <div class="card-body">
        <div class="row p-0">
          <div class="col-md-6 p-0">
            <table class="table">
              <tr>
                <th class="w-25">Waktu Pinjam</th>
                <td class="w-50">{{ $pinjam->jam_awal }}, {{ $tanggal_awal }}</td>
              </tr>
              <tr>
                <th class="w-25">Waktu Kembali</th>
                <td class="w-50">{{ $pinjam->jam_akhir }}, {{ $tanggal_akhir }}</td>
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
                <td class="w-50">
                  @if ($pinjam->ruang->laborans)
                  @foreach ($pinjam->ruang->laborans as $laboran)
                  - {{ $laboran->nama }}<br>
                  @endforeach
                  @else
                  -
                  @endif
                </td>
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
                <th class="text-center">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($detail_pinjams as $detail_pinjam)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $detail_pinjam->barang->nama }}</td>
                <td>{{ ucfirst($detail_pinjam->barang->kategori) }}</td>
                <td class="text-center">{{ $detail_pinjam->jumlah }} {{ ucfirst($detail_pinjam->satuan->nama) }}</td>
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