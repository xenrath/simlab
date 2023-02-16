@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Peminjaman</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Peminjaman</h4>
      </div>
      <div class="card-body p-0">
        <div class="p-4">
          <form action="{{ url('laboran/peminjaman') }}" method="get" id="get-keyword">
            <div class="float-xs-right float-sm-right float-left mb-3">
              <div class="input-group">
                <input type="search" class="form-control" name="keyword" placeholder="Cari"
                  value="{{ Request::get('keyword') }}" autocomplete="off" onsubmit="event.preventDefault();
                document.getElementById('get-keyword').submit();">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
        <table class="table table-striped">
          <tr>
            <th class="text-center">No.</th>
            <th>Nama Peminjam</th>
            <th>Waktu Pinjam</th>
            <th class="text-center">Barang</th>
            <th class="text-center">Status</th>
          </tr>
          @forelse($pinjams as $pinjam)
          <tr>
            <td class="text-center align-middle">{{ $loop->iteration }}</td>
            <td class="align-middle">{{ $pinjam->peminjam->nama }}</td>
            @php
            $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
            $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
            @endphp
            <td class="align-middle">
              @if ($tanggal_awal == $tanggal_akhir)
              {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}, {{ $tanggal_awal }}
              @else
              {{ $pinjam->jam_awal }}, {{ $tanggal_awal }} <br> {{ $pinjam->jam_akhir }}, {{ $tanggal_akhir }}
              @endif
            </td>
            <td class="text-center align-middle">
              <a href="{{ url('laboran/riwayat/detail/' . $pinjam->id) }}" class="btn btn-info">
                <i class="fas fa-eye"></i>
                <span class="d-none d-md-inline">&nbsp;Lihat</span>
              </a>
            </td>
            <td class="text-center align-middle">
              @if ($pinjam->status == 'dibatalkan')
              <span class="badge badge-danger">Dibatalkan</span>
              @elseif ($pinjam->status == 'ditolak')
              <span class="badge badge-danger">Ditolak</span>
              @elseif ($pinjam->status == 'selesai')
              <span class="badge badge-success">Selesai</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td class="text-center" colspan="5">- Data tidak ditemukan -</td>
          </tr>
          @endforelse
        </table>
      </div>
      <div class="card-footer">
        <div class="pagination">
          {{ $pinjams->appends(Request::all())->links('pagination::bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
</section>
@endsection