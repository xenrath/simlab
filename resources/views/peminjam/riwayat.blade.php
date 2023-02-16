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
                <th class="text-center">Barang</th>
                <th class="text-center">Status</th>
                {{-- <th class="text-center">Opsi</th> --}}
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
                @endphp
                <td class="align-middle">
                  @if ($tanggal_awal == $tanggal_akhir)
                  {{ $jam_awal }} - {{ $jam_akhir }}, {{ $tanggal_awal }}
                  @else
                  {{ $jam_awal }}, {{ $tanggal_awal }} <br> {{ $jam_akhir }}, {{ $tanggal_akhir }}
                  @endif
                </td>
                <td class="align-middle text-wrap">{{ $pinjam->ruang->nama }}</td>
                <td class="text-center align-middle">
                  <a href="{{ url('peminjam/riwayat/detail/' . $pinjam->id) }}" class="btn btn-info">
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
                  <span class="badge badge-danger">Selesai</span>
                  @endif
                </td>
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