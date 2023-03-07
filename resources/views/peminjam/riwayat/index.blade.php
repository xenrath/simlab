@extends('layouts.app')

@section('title', 'Data Riwayat')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Riwayat</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Riwayat Peminjaman</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Waktu Peminjaman</th>
                  <th>Ruang (Lab)</th>
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
                    @endphp
                    <td class="align-middle">
                      @if ($tanggal_awal == $tanggal_akhir)
                        {{ $jam_awal }} - {{ $jam_akhir }}, {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                      @else
                        {{ $jam_awal }}, {{ $tanggal_awal }} <br> {{ $jam_akhir }},
                        {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                      @endif
                    </td>
                    <td class="align-middle text-wrap">{{ $pinjam->ruang->nama }}</td>
                    <td>
                      <a href="{{ url('peminjam/normal/riwayat/' . $pinjam->id) }}" class="btn btn-info">
                        Lihat
                      </a>
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
