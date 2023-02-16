@extends('layouts.app')

@section('title', 'Pengembalian')

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
        <div class="p-4">
          <form action="{{ url('laboran/pengembalian') }}" method="get" id="get-keyword">
            <div class="float-xs-right float-right mb-3">
              <div class="input-group">
                <input type="search" class="form-control" name="keyword" placeholder="Cari"
                  value="{{ Request::get('keyword') }}" autocomplete="off" onsubmit="event.preventDefault();
                document.getElementById('get-keyword').submit();">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-striped">
            <tr>
              <th class="text-center">No.</th>
              <th>Nama Peminjam</th>
              <th>Waktu Pinjam</th>
              <th class="text-center">Barang</th>
              <th>Status</th>
              <th class="text-center">Opsi</th>
            </tr>
            @forelse($pinjams as $pinjam)
            <tr>
              <td class="text-center align-middle">{{ $loop->iteration }}</td>
              <td class="align-middle">{{ $pinjam->peminjam->nama }}</td>
              @php
              $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
              $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
              @endphp
              <td class="align-middle">
                @if ($tanggal_awal == $tanggal_akhir)
                {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}, {{ $tanggal_awal }}
                @else
                {{ $pinjam->jam_awal }}, {{ $tanggal_awal }} <br> {{ $pinjam->jam_akhir }}, {{ $tanggal_akhir }}
                @endif
              </td>
              <td class="text-center align-middle">
                <a href="{{ url('laboran/pengembalian/detail/' . $pinjam->id) }}" class="btn btn-info">
                  <i class="fas fa-eye"></i>
                  <span class="d-none d-md-inline">&nbsp;Lihat</span>
                </a>
              </td>
              <td>
                @php
                $jam_awal = $pinjam->jam_awal;
                $jam_akhir = $pinjam->jam_akhir;
                $now = Carbon\Carbon::now();
                $expire = date('Y-m-d H:i:s', strtotime($pinjam->tanggal_akhir . $jam_akhir));
                @endphp
                @if ($now > $expire)
                <span class="badge badge-danger">Kadaluarsa</span>
                @else
                -
                @endif
              </td>
              <td class="text-center align-middle">
                <!-- <a href="{{ url('laboran/pengembalian/cetak/' . $pinjam->id) }}" class="btn btn-outline-primary">
                  <i class="fas fa-print"></i>
                  <span class="d-none d-md-inline">&nbsp;Cetak</span>
                </a> -->
                <a href="{{ url('laboran/pengembalian/konfirmasi/' . $pinjam->id) }}" class="btn btn-primary">
                  <i class="fas fa-clipboard-check"></i>
                  <span class="d-none d-md-inline">&nbsp;Konfirmasi</span>
                </a>
                @if ($now > $expire)
                @php
                $jam = date('G');
                if ($jam >= '0' && $jam <= '11' ) { $waktu='Pagi' ; } else if ($jam>= '12' && $jam <= '14' ) {
                    $waktu='Siang' ; } else if ($jam>= '15' && $jam <= '18' ) { $waktu='Sore' ; } else { $waktu='malam'
                      ; } @endphp <a
                      href="https://web.whatsapp.com/send?phone=62{{ $pinjam->peminjam->telp }}&text=Selamat%20{{ $waktu }}%20{{ $pinjam->peminjam->nama }}%0ASekedar%20mengingatkan,%20masa%20peminjaman%20barang%20Anda%20telah%20habis.%20Mohon%20untuk%20segera%20dikembalikan%0ATerimakasih"
                      target="_blank" class="btn btn-success">
                      <i class="fab fa-whatsapp"></i>
                      </a>
                      @endif
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