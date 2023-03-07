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
                      @php
                        $jam = date('G');
                        if ($jam >= '0' && $jam <= '11') {
                            $waktu = 'Pagi';
                        } elseif ($jam >= '12' && $jam <= '14') {
                            $waktu = 'Siang';
                        } elseif ($jam >= '15' && $jam <= '18') {
                            $waktu = 'Sore';
                        } else {
                            $waktu = 'malam';
                        }
                      @endphp
                      @if ($pinjam->status == 'disetujui')
                        @if ($now > $expire)
                          <div class="badge badge-danger">Kadaluarsa</div>
                          <br>
                          <a href="https://wa.me/+62{{ $pinjam->peminjam->telp }}?text=Selamat%20{{ $waktu }}%20{{ $pinjam->peminjam->nama }}%20dari%20{{ $pinjam->peminjam->alamat }}.%0ASaya%20dari%20Universitas%20Bhamada%20Slawi%20mengingatkan,%20masa%20peminjaman%20barang%20Anda%20telah%20habis.%20Mohon%20untuk%20segera%20dikembalikan%0ATerimakasih"
                            target="_blank" class="btn btn-success mt-1">
                            Hubungi
                          </a>
                        @else
                          <div class="badge badge-primary">Aktif</div>
                        @endif
                      @elseif ($pinjam->status == 'selesai')
                        <div class="badge badge-success">Selesai</div>
                      @endif
                    </td>
                    <td class="align-top py-3">
                      <a href="{{ url('admin/peminjaman/' . $pinjam->id) }}" class="btn btn-info">
                        Detail
                      </a>
                      @if ($pinjam->status == 'disetujui')
                        <br>
                        <a href="{{ url('admin/peminjaman/konfirmasi_selesai/' . $pinjam->id) }}"
                          class="btn btn-primary mt-1">
                          Konfirmasi
                        </a>
                      @endif
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
