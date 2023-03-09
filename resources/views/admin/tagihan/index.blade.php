@extends('layouts.app')

@section('title', 'Tagihan')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Tagihan</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Data Tagihan</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Peminjam</th>
                  <th>Waktu</th>
                  <th>Opsi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pinjams as $key => $pinjam)
                  <tr>
                    <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                    <td class="align-top py-3">
                      {{ $pinjam->peminjam->alamat }} <br>
                      ({{ $pinjam->peminjam->nama }})
                    </td>
                    @php
                      $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                      $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                    @endphp
                    <td class="align-top py-3">
                      {{ $tanggal_awal }} - <br>
                      {{ $tanggal_akhir }}
                    </td>
                    <td class="align-top py-3">
                      <a href="{{ url('admin/tagihan/' . $pinjam->id) }}" class="btn btn-primary mr-2">
                        Konfirmasi
                      </a>
                      <br>
                      <a href="{{ url('admin/tagihan/hubungi/' . $pinjam->id) }}" target="_blank"
                        class="btn btn-success mt-1">
                        Hubungi
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center">- Data tidak ditemukan -</td>
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
