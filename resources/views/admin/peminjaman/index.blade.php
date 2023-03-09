@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Peminjaman</h1>
      <div class="section-header-button">
        <a href="{{ url('admin/peminjaman/create') }}" class="btn btn-primary">Buat Peminjaman</a>
      </div>
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
                  <th>Peminjam</th>
                  <th>Waktu</th>
                  <th>Status</th>
                  <th>Opsi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pinjams as $pinjam)
                  <tr>
                    <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                    <td class="align-top py-3">
                      {{ $pinjam->peminjam->alamat }} <br>
                      ({{ $pinjam->peminjam->nama }})
                    </td>
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
                    <td class="align-top py-3">
                      @if ($pinjam->status == 'disetujui')
                        @if ($now > $expire)
                          <div class="badge badge-danger">Kadaluarsa</div>
                        @else
                          <div class="badge badge-primary">Aktif</div>
                        @endif
                      @elseif ($pinjam->status == 'selesai')
                        <div class="badge badge-success">Selesai</div>
                      @endif
                    </td>
                    <td class="align-top py-3">
                      @if ($pinjam->status != 'disetujui')
                        <a href="{{ url('admin/peminjaman/' . $pinjam->id) }}" class="btn btn-info">
                          Detail
                        </a>
                      @else
                        <a href="{{ url('admin/peminjaman/konfirmasi/' . $pinjam->id) }}"
                          class="btn btn-primary mt-1">
                          Konfirmasi
                        </a>
                      @endif
                      <br>
                      <a href="{{ url('admin/peminjaman/hubungi/' . $pinjam->id) }}" target="_blank"
                        class="btn btn-success mt-1">
                        Hubungi
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
    function modalHapus(id) {
      $("#hapus-" + id).submit();
    }
  </script>
@endsection
