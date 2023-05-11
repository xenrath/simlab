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
                <th>Praktik</th>
                <th>Status</th>
                <th>Opsi</th>
              </tr>
              @forelse($pinjams as $pinjam)
                <tr>
                  <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                  @if ($pinjam->praktik_id == '1' || $pinjam->praktik_id == '2')
                    <td class="align-top py-3">
                      <span class="bullet"></span>&nbsp;{{ $pinjam->kelompoks->first()->m_ketua->nama }} (Ketua) <br>
                      @php
                        $kelompok = $pinjam->kelompoks->first();
                      @endphp
                      @if ($kelompok->anggota)
                      @foreach ($kelompok->anggota as $anggota)
                        <span class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                        <br>
                      @endforeach
                      @endif
                    </td>
                  @else
                    <td class="align-top py-3">
                      {{ $pinjam->peminjam->nama }}
                    </td>
                  @endif
                  @php
                    $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                    $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                  @endphp
                  <td class="align-top py-3">
                    @if ($pinjam->praktik_id == '3')
                      {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                    @else
                      {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }} <br> {{ $tanggal_awal }}
                    @endif
                  </td>
                  <td class="align-top py-3">
                    @if ($pinjam->praktik_id != null)
                      @if ($pinjam->praktik_id == '1')
                        {{ $pinjam->praktik->nama }} <br>
                        ({{ $pinjam->ruang->nama }})
                      @else
                        {{ $pinjam->praktik->nama }} <br>
                        ({{ $pinjam->keterangan }})
                      @endif
                    @else
                      -
                    @endif
                  </td>
                  <td class="align-top py-3">
                    @php
                      $jam_awal = $pinjam->jam_awal;
                      $jam_akhir = $pinjam->jam_akhir;
                      $now = Carbon\Carbon::now();
                      $expire = date('Y-m-d H:i:s', strtotime($pinjam->tanggal_akhir . $jam_akhir));
                    @endphp
                    @if ($now > $expire)
                      <span class="badge badge-danger">Kadaluarsa</span>
                    @else
                      <span class="badge badge-primary">Aktif</span>
                    @endif
                    <br>
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
                    <a href="{{ url('laboran/pengembalian-new/' . $pinjam->id . '/hubungi') }}" target="_blank" class="btn btn-success mt-1">
                      Hubungi
                    </a>
                  </td>
                  <td class="align-top py-3">
                    <a href="{{ url('laboran/pengembalian-new/' . $pinjam->id . '/konfirmasi') }}"
                      class="btn btn-primary">
                      Konfirmasi
                    </a>
                    <form action="{{ url('laboran/pengembalian-new/' . $pinjam->id) }}" method="POST"
                      id="hapus-{{ $pinjam->id }}">
                      @csrf
                      @method('delete')
                      <button type="button" class="btn btn-danger mt-1"
                        data-confirm="Batalkan Peminjaman?|Apakah anda yakin akan menghapus peminjaman ini?"
                        data-confirm-yes="modalHapus({{ $pinjam->id }})">Hapus</button>
                    </form>
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
      </div>
    </div>
  </section>
  <script>
    function modalHapus(id) {
      $("#hapus-" + id).submit();
    }
  </script>
@endsection
