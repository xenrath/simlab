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
              <tr>
                <th class="text-center">No.</th>
                <th>Peminjam</th>
                <th>Waktu</th>
                <th>Ruang (Lab)</th>
                <th>Status</th>
                <th>Opsi</th>
              </tr>
              @forelse($pinjams as $pinjam)
                <tr>
                  <td class="text-center py-3 align-top">{{ $loop->iteration }}</td>
                  <td class="py-3 align-top">{{ $pinjam->peminjam->nama }}</td>
                  @php
                    $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                    $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                    $now = Carbon\Carbon::now()->format('Y-m-d');
                    $expire = date('Y-m-d', strtotime($pinjam->tanggal_akhir));
                  @endphp
                  <td class="py-3 align-top">
                    {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                  </td>
                  <td class="py-3 align-top">
                    {{ $pinjam->ruang->nama }}
                  </td>
                  <td class="py-3 align-top">
                    @if ($now > $expire)
                      <span class="badge badge-danger">Kadaluarsa</span>
                    @else
                      <span class="badge badge-primary">Aktif</span>
                    @endif
                  </td>
                  <td class="py-3 align-top">
                    <a href="{{ url('laboran/pengembalian/' . $pinjam->id . '/konfirmasi') }}" class="btn btn-primary mr-1">
                      Konfirmasi
                    </a>
                    <br>
                    <a href="{{ url('laboran/pengembalian/hubungi/' . $pinjam->id) }}"
                      target="_blank" class="btn btn-success mt-1">
                      Hubungi
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td class="text-center" colspan="6">- Data tidak ditemukan -</td>
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
