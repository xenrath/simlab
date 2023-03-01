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
                <th>Praktik</th>
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
                      @foreach ($kelompok->anggota as $anggota)
                        <span class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                        <br>
                      @endforeach
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
                    <a href="{{ url('laboran/peminjaman-new/' . $pinjam->id) }}" class="btn btn-info mr-1">
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
      </div>
    </div>
  </section>
@endsection
