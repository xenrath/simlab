@extends('layouts.app')

@section('title', 'Detail Data User')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('peminjam/pengembalian') }}" class="btn btn-secondary">
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
          $expire = date('Y-m-d H:i:s', strtotime($pinjam->tanggal_akhir . $jam_akhir));
          @endphp
          @if ($now > $expire)
          <span class="badge badge-danger">Kadaluarsa</span>
          @else
          <span class="badge badge-primary">Aktif</span>
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
    </div>
    <div class="card">
      <div class="card-header">
        <h4>Barang</h4>
        @php
        $tanggal_awal = date('d/m/Y', strtotime($pinjam->tanggal_awal));
        $tanggal_akhir = date('d/m/Y', strtotime($pinjam->tanggal_akhir));
        $jam_awal = $pinjam->jam_awal;
        $jam_akhir = $pinjam->jam_akhir;
        $now = Carbon\Carbon::now();
        $expire = date('Y-m-d G:i:s', strtotime($pinjam->tanggal_akhir . $jam_akhir));
        @endphp
        @if ($now > $expire)
        <span class="badge badge-danger">Harap kembalikan barang segera.</span>
        @endif
      </div>
      <div class="card-body p-0">
        <div class="table-responsive-sm">
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Nomor Inventaris</th>
                <th>Nama Barang</th>
                <th class="text-center">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($barangs as $barang)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $barang->barang->kode }}</td>
                <td>{{ $barang->barang->nama }}</td>
                <td class="text-center">{{ $barang->jumlah }} {{ ucfirst($barang->satuan->nama) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h4>Bahan habis pakai</h4>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive-sm">
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Nomor Inventaris</th>
                <th>Nama Barang</th>
                <th class="text-center">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bahans as $bahan)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $bahan->barang->kode }}</td>
                <td>{{ $bahan->barang->nama }}</td>
                <td class="text-center">{{ $bahan->jumlah }} {{ ucfirst($bahan->satuan->nama) }}</td>
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