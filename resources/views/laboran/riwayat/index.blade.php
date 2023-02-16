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
                <th>Waktu Peminjaman</th>
                <th>Ruang Lab</th>
                <th>Opsi</th>
              </tr>
              @forelse($pinjams as $pinjam)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  @php
                    $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                    $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                  @endphp
                  <td>
                    @if ($tanggal_awal == $tanggal_akhir)
                      {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}, {{ $tanggal_awal }}
                    @else
                      {{ $pinjam->jam_awal }}, {{ $tanggal_awal }} <br> {{ $pinjam->jam_akhir }}, {{ $tanggal_akhir }}
                    @endif
                  </td>
                  <td>
                    {{ $pinjam->ruang->nama }}
                  </td>
                  <td>
                    <a href="{{ url('laboran/riwayat/' . $pinjam->id) }}" class="btn btn-info">
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
