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
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $pinjam->peminjam->nama }}</td>
                  @php
                    $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                    $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                    $now = Carbon\Carbon::now()->format('Y-m-d');
                    $expire = date('Y-m-d', strtotime($pinjam->tanggal_akhir));
                  @endphp
                  <td>
                    {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                  </td>
                  <td>
                    {{ $pinjam->ruang->nama }}
                  </td>
                  <td>
                    @if ($now > $expire)
                      <span class="badge badge-danger">Kadaluarsa</span>
                    @else
                      <span class="badge badge-primary">Aktif</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ url('laboran/pengembalian/' . $pinjam->id . '/konfirmasi') }}" class="btn btn-primary mr-1">
                      Konfirmasi
                    </a>
                    <a href="https://wa.me/+62{{ $pinjam->peminjam->telp }}"
                      target="_blank" class="btn btn-success">
                      <i class="fab fa-whatsapp"></i>
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
