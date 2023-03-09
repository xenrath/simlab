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
                  <th>Waktu</th>
                  <th>Ruang (Lab)</th>
                  <th>Status</th>
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
                      $now = Carbon\Carbon::now();
                      $expire = date('Y-m-d', strtotime($pinjam->tanggal_akhir));
                    @endphp
                    <td class="align-top py-3">
                      {{ $tanggal_awal }} - <br>
                      {{ $tanggal_akhir }}
                    </td>
                    <td class="align-top py-3">{{ $pinjam->ruang->nama }}</td>
                    <td class="align-top py-3">
                      @if ($now > $expire)
                        <span class="badge badge-danger">Kadaluarsa</span>
                      @else
                        <span class="badge badge-primary">Aktif</span>
                      @endif
                    </td>
                    <td class="align-top py-3">
                      <a href="{{ url('peminjam/normal/pengembalian/' . $pinjam->id) }}" class="btn btn-info">
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
