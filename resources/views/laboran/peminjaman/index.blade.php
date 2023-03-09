@extends('layouts.app')

@section('title', 'Pinjam Barang')

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
              <tr>
                <th class="text-center">No.</th>
                <th>Peminjam</th>
                <th>Waktu</th>
                <th>Ruang (Lab)</th>
                <th>Opsi</th>
              </tr>
              @forelse($pinjams as $pinjam)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $pinjam->peminjam->nama }}</td>
                  @php
                    $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                    $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                  @endphp
                  <td>
                    {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                  </td>
                  <td>
                    {{ $pinjam->ruang->nama }}
                  </td>
                  <td>
                    <a href="{{ url('laboran/peminjaman/' . $pinjam->id) }}" class="btn btn-info mr-1">
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
