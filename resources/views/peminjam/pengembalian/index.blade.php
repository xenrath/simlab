@extends('layouts.app')

@section('title', 'Data Pengembalian')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Pengembalian</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Pengembalian</h4>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Waktu Peminjaman</th>
                <th>Ruang (Lab)</th>
                <th>Status</th>
                <th>Opsi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pinjams as $pinjam)
              <tr>
                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                @php
                $tanggal_awal = date('d/m/Y', strtotime($pinjam->tanggal_awal));
                $tanggal_akhir = date('d/m/Y', strtotime($pinjam->tanggal_akhir));
                $jam_awal = $pinjam->jam_awal;
                $jam_akhir = $pinjam->jam_akhir;
                $now = Carbon\Carbon::now();
                $expire = date('Y-m-d H:i:s', strtotime($pinjam->tanggal_akhir . $jam_akhir));
                @endphp
                <td class="align-middle">
                  @if ($tanggal_awal == $tanggal_akhir)
                  {{ $jam_awal }} - {{ $jam_akhir }}, {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                  @else
                  {{ $jam_awal }}, {{ $tanggal_awal }} <br> {{ $jam_akhir }}, {{ $tanggal_akhir }}
                  @endif
                </td>
                <td class="align-middle text-wrap">{{ $pinjam->ruang->nama }}</td>
                <td class="align-middle">
                  @if ($now > $expire)
                  <span class="badge badge-danger">Kadaluarsa</span>
                  @else
                  <span class="badge badge-primary">Aktif</span>
                  @endif
                </td>
                <td class="align-middle">
                  <a href="{{ url('peminjam/normal/pengembalian/' . $pinjam->id) }}" class="btn btn-info">
                    Lihat
                  </a>
                </td>
                {{-- <td class="text-center align-middle">
                  <a href="{{ url('peminjam/normal/pengembalian/cetak/' . $pinjam->id) }}" class="btn btn-dark">
                    <i class="fas fa-print"></i>
                    <span class="d-none d-md-inline">&nbsp;Cetak</span>
                  </a>
                </td> --}}
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  function modalBatal(id) {
    $("#batal-" + id).submit();
  }
</script>
@endsection