@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('laboran/peminjaman') }}" class="btn btn-secondary">
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
          @elseif ($pinjam->status == 'disetujui')
          <span class="badge badge-primary">Disetujui</span>
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
                  {{ $pinjam->keterangan }}
                  @else
                  -
                  @endif
                </td>
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
                <td class="w-50">{{ $pinjam->ruang->laboran->nama }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Nama Barang</th>
                <th class="text-center">Kategori</th>
                <th class="text-center">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($detail_pinjams as $detail_pinjam)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $detail_pinjam->barang->nama }}</td>
                <td class="text-center">{{ ucfirst($detail_pinjam->barang->kategori) }}</td>
                <td class="text-center">{{ $detail_pinjam->jumlah }}
                  {{ ucfirst($detail_pinjam->satuan->nama) }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer">
        <a href="{{ url('laboran/peminjaman/setujui/' . $pinjam->id) }}" class="btn btn-primary float-left">
          <i class="fas fa-check"></i>
          <span class="d-none d-md-inline">&nbsp;Setujui</span>
        </a>
        <a href="{{ url('laboran/peminjaman/tolak/' . $pinjam->id) }}" class="btn btn-danger float-right">
          <i class="fas fa-times"></i>
          <span class="d-none d-md-inline">&nbsp;Tolak</span>
        </a>
      </div>
    </div>
  </div>
</section>
@endsection