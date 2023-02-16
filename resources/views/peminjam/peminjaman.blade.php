@extends('layouts.app')

@section('title', 'Data Peminjaman')

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
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Waktu Peminjaman</th>
                <th>Ruang (Lab)</th>
                <th class="text-center">Barang</th>
                <th class="text-center">Opsi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pinjams as $pinjam)
              <tr>
                <td class="text-center align-middle">{{ $loop->iteration }}</td>
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
                <td class="align-middle text-wrap">{{ $pinjam->ruang->nama }}</td>
                <td class="text-center align-middle">
                  <a href="{{ url('peminjam/peminjaman/detail/' . $pinjam->id) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i>
                    <span class="d-none d-md-inline">&nbsp;Detail</span>
                  </a>
                </td>
                <td class="text-center align-middle">
                  <form action="{{ url('peminjam/peminjaman/batal/' . $pinjam->id) }}" method="GET"
                    id="batal-{{ $pinjam->id }}">
                    <a href="{{ url('peminjam/peminjaman/cetak/' . $pinjam->id) }}" class="btn btn-primary mr-1">
                      <i class="fas fa-print"></i>
                      <span class="d-none d-md-inline">&nbsp;Cetak</span>
                    </a>
                    <button type="submit" class="btn btn-danger"
                      data-confirm="Batalkan Peminjaman?|Apakah anda yakin akan membatalkan peminjaman ini?"
                      data-confirm-yes="modalBatal({{ $pinjam->id }})">
                      <i class="fas fa-times"></i>
                      <span class="d-none d-md-inline">&nbsp;Batalkan</span>
                    </button>
                  </form>
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