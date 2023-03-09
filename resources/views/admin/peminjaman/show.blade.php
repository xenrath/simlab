@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('admin/peminjaman') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
      </a>
      </div>
      <h1>Peminjaman</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Detail Peminjaman</h4>
          <div class="card-header-action">
            @php
              $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
              $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
              $jam_awal = $pinjam->jam_awal;
              $jam_akhir = $pinjam->jam_akhir;
              $now = Carbon\Carbon::now();
              $expire = date('Y-m-d', strtotime($pinjam->tanggal_akhir));
            @endphp
            @if ($pinjam->status == 'disetujui')
              @if ($now > $expire)
                <div class="badge badge-danger">Kadaluarsa</div>
              @else
                <div class="badge badge-primary">Aktif</div>
              @endif
            @elseif ($pinjam->status == 'selesai')
              <div class="badge badge-success">Selesai</div>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Instansi</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->peminjam->alamat }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Peminjam</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->peminjam->nama }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>No. Telepon</strong>
                </div>
                <div class="col-md-8">
                  +62{{ $pinjam->peminjam->telp }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Detail Alat</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nama Barang</th>
                  <th>Ruang</th>
                  <th class="text-center">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($detail_pinjams as $detail_pinjam)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $detail_pinjam->barang->nama }}</td>
                    <td>{{ $detail_pinjam->barang->ruang->nama }}</td>
                    <td class="text-center">{{ $detail_pinjam->jumlah }} {{ ucfirst($detail_pinjam->satuan->nama) }}
                    </td>
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
