@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Peminjaman</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Riwayat Peminjaman</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped">
              <tr>
                <th class="text-center">No.</th>
                <th>Peminjaman</th>
                <th>Waktu</th>
                <th>Ruang (Lab)</th>
                <th>Opsi</th>
              </tr>
              @forelse($pinjams as $pinjam)
                <tr>
                  <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                  <td class="align-top py-3">{{ $pinjam->peminjam->nama }}</td>
                  @php
                    $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                    $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                  @endphp
                  <td class="align-top py-3">
                    {{ $tanggal_awal }} - <br>
                    {{ $tanggal_akhir }}
                  </td>
                  <td class="align-top py-3">
                    {{ $pinjam->ruang->nama }}
                  </td>
                  <td class="align-top py-3">
                    <a href="{{ url('laboran/riwayat/' . $pinjam->id) }}" class="btn btn-info mr-1">
                      Lihat
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td class="text-center" colspan="5">- Data tidak ditemukan -</td>
                </tr>
              @endforelse
            </table>
          </div>
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
