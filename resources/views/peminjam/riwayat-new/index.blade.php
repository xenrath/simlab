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
                  <th>Waktu</th>
                  <th>Praktik</th>
                  <th>Opsi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pinjams as $pinjam)
                  <tr>
                    <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                    @php
                      $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                      $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                    @endphp
                    <td class="align-top py-3">
                      @if ($pinjam->praktik_id == '3')
                        {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                      @else
                        {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}, {{ $tanggal_akhir }}
                      @endif
                    </td>
                    <td class="align-top py-3 text-wrap">
                      @if ($pinjam->praktik_id != null)
                        @if ($pinjam->praktik_id == '1')
                          {{ $pinjam->praktik->nama }} <br>
                          ({{ $pinjam->ruang->nama }})
                        @else
                          {{ $pinjam->praktik->nama }} <br>
                          ({{ $pinjam->keterangan }})
                        @endif
                      @else
                        -
                      @endif
                    </td>
                    <td class="align-top py-3">
                      <a href="{{ url('peminjam/normal/riwayat-new/' . $pinjam->id) }}" class="btn btn-info mr-1">
                        Detail
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
